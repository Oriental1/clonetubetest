<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h2>Upload Excel File</h2>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'excelFile')->fileInput() ?>

    <button>Upload</button>

<?php ActiveForm::end(); ?>

