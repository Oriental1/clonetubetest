<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
use frontend\models\excel\UploadForm;

class ExcelController extends Controller
{
    public function actionIndex()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');

            if ($model->validate()) {
                $filePath = 'uploads/excelsheets/' . $model->excelFile->baseName . '.' . $model->excelFile->extension;
                $model->excelFile->saveAs($filePath);

                // Store file path in session 
                Yii::$app->session->set('uploadedFileName', $filePath);


                return $this->redirect(['show-excel', 'filePath' => $filePath]);
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionShowExcel($filePath)
    {
        // Ensure the file exists before trying to load it
        if (!file_exists($filePath)) {
            return $this->redirect(['excel/index']);
        }
    
        // Load the spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $data = [];
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }
    
        return $this->render('show-excel', [
            'data' => $data,
            'filePath' => $filePath
        ]);
    }
    

    public function actionSaveToDb()
    {
        $request = Yii::$app->request;
        $excelData = unserialize(base64_decode($request->post('excelData')));
        $tableName = trim($request->post('tableName'));

        // Retrieve file path from session
        $filePath = Yii::$app->session->get('uploadedFileName');
    
        if (!$tableName || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName)) {
            Yii::$app->session->setFlash('error', 'Invalid table name.');
            return $this->redirect(Yii::$app->request->referrer);
        }
    
        $db = Yii::$app->db;
        if ($db->schema->getTableSchema($tableName, true) !== null) {
            Yii::$app->session->setFlash('error', 'Table already exists.');
            return $this->redirect(Yii::$app->request->referrer);
        }
    
        // Extract and sanitize column names from the first row
        $columns = array_map(function ($col) {
            $col = preg_replace('/\W+/', '_', strtolower(trim($col))); // Replace non-word characters with "_"
            return preg_match('/^\d/', $col) ? "col_" . $col : $col; // Ensure column names do not start with a number
        }, $excelData[0]);
        
        $columns = array_unique($columns);
    
        $transaction = $db->beginTransaction(); // Start transaction
        try {
            // Create table SQL
            $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (id INT PRIMARY KEY AUTO_INCREMENT, ";
            foreach ($columns as $column) {
                $sql .= "`{$column}` TEXT, ";
            }
            $sql = rtrim($sql, ', ') . ')';
    
            // Execute the table creation query
            $db->createCommand($sql)->execute();
    
            // Insert the table name into the table_registry
            $registrySql = "INSERT INTO table_registry (table_name) VALUES (:tableName)";
            $db->createCommand($registrySql)->bindValue(':tableName', $tableName)->execute();
    
            // Prepare data for batch insert (exclude header row)
            $rows = array_slice($excelData, 1);
            $batchSize = 500; // Define batch size
    
            foreach (array_chunk($rows, $batchSize) as $batch) {
                $db->createCommand()->batchInsert($tableName, $columns, $batch)->execute();
            }

            // Delete the uploaded file before committing
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Destroy session after successful processing
            Yii::$app->session->remove('uploadedFileName');
    
            $transaction->commit(); // Commit transaction
            
            return $this->redirect(['excel/view-table', 'tableName' => $tableName]);
        } catch (\Exception $e) {
            $transaction->rollBack(); // Rollback on failure
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    

    public function actionViewTable($tableName)
    {
        $db = Yii::$app->db;
        $tableSchema = $db->schema->getTableSchema($tableName);

        if (!$tableSchema) {
            throw new \yii\web\NotFoundHttpException("Table does not exist.");
        }

        // Fetch first 100 rows
        $query = (new \yii\db\Query())->from($tableName);
        $rows = $query->all();
        $columns = $tableSchema->columnNames;

        $dataProvider = new ArrayDataProvider([
            'allModels' => $rows,
            'pagination' => ['pageSize' => 100],
        ]);
    
        return $this->render('view-table', [
            'tableName' => $tableName,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    public function actionDropTable($tableName)
    {
        $db = Yii::$app->db;
        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $tableName); // Sanitize table name
    
        if ($db->schema->getTableSchema($tableName, true) !== null) {
            $transaction = $db->beginTransaction(); // Start transaction
            try {
                // Drop the table
                $db->createCommand("DROP TABLE `$tableName`")->execute();
    
                // Remove the table entry from table_registry
                $db->createCommand("DELETE FROM table_registry WHERE table_name = :tableName")
                    ->bindValue(':tableName', $tableName)
                    ->execute();
    
                $transaction->commit(); // Commit transaction
                Yii::$app->session->setFlash('success', "Table '$tableName' has been deleted.");
            } catch (\Exception $e) {
                $transaction->rollBack(); // Rollback on failure
                Yii::$app->session->setFlash('error', "Error: " . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', "Table '$tableName' does not exist.");
        }
    
        return $this->redirect(['excel/index']);
    }

    public function actionDeleteRow()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $tableName = $request->post('tableName');
            $id = $request->post('id');

            if ($tableName && $id) {
                $db = Yii::$app->db;
                $db->createCommand()->delete($tableName, ['id' => $id])->execute();

                Yii::$app->session->setFlash('success', 'Row deleted successfully.');
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    
}

?>