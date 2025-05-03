<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$tables = Yii::$app->db->createCommand("SELECT table_name FROM table_registry")->queryColumn();

?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="list-group">
                <h5>Database Tables</h5>
                <?php foreach ($tables as $table): ?>
                    <a href="<?= yii\helpers\Url::to(['excel/view-table', 'tableName' => $table]) ?>" 
                    class="list-group-item list-group-item-action">
                        <?= Html::encode($table) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content (Upload Form) -->
        <div class="col">
            <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
                <div class="card p-4" style="max-width: 500px; width: 100%;">
                    <h2 class="text-center mb-4">Upload Excel File</h2>

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <div class="d-flex justify-content-center">
                        <div class="form-group" style="max-width: 400px; width: 100%;">
                            <?= $form->field($model, 'excelFile')
                                ->fileInput(['class' => 'form-control']) 
                                ->label('Choose Excel File', ['class' => 'text-center']) ?>
                        </div>
                    </div>
                    <br>

                    <div class="form-group text-center">
                        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

