<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\Pagination;
use frontend\models\excel\Excel;
use frontend\models\excel\Data;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

                return $this->redirect(['show-excel', 'filePath' => $filePath]);
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionShowExcel($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $data = [];
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }

        return $this->render('show-excel', ['data' => $data]);
    }
}


?>