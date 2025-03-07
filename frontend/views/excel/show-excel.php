<?php
use common\helpers\ExcelHelper;
use common\helpers\Html;
use yii\helpers\Html as HelpersHtml;
use yii\widgets\ActiveForm;
use yii\web\YiiAsset;

$finalData = ExcelHelper::trimExcelData($data);

$this->registerCssFile('@web/css/excel.css', ['depends' => [YiiAsset::class]]);

?>

<h2>Preview</h2>

<div class="container">
    <!-- Sidebar with links -->
    <div class="sidebar">
        <a href="<?= Yii::$app->request->referrer ?>">Upload Another File</a>
        <a href="<?= Yii::$app->urlManager->createUrl(['excel/custom-header']) ?>">Custom Header</a>
    </div>
    
    <!-- Table displaying the Excel data -->
    <div class="table-container">
        <?php $form = ActiveForm::begin(['action' => ['excel/save-to-db'], 'method' => 'post']); ?>
            <input type="hidden" name="excelData" value="<?= base64_encode(serialize($finalData)) ?>">
            <?= HelpersHtml::submitButton('Commit to Database', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
        <table border="1">
            <?php foreach ($finalData as $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td><?= htmlspecialchars(trim($cell) !== 'NULL' ? trim($cell) : '') ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
