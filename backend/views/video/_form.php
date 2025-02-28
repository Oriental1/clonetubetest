<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Video $model */
/** @var yii\bootstrap5\ActiveForm $form */

\backend\assets\TagsInputAsset::register($this);
?>

<div class="video-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-sm-8">

            <?php echo $form->errorSummary($model) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <label><?php echo $model->getAttributeLabel(attribute: 'thumbnail') ?></label>
                <input type="file" class="custom-file-input"
                       id="thumbnail" name="thumbnail">
            </div>
        
            <?= $form->field($model, 'tags', [
                'inputOptions' => ['data-role' => 'tagsinput']
            ])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-4">
            
            <div class="ratio ratio-16x9 mb-3">
                <video src="<?php echo $model->getVideoLink() ?>" poster="<?php echo $model->getThumbnailLink() ?>" controls></video>
            </div>
            
            <div class="mb-3">
                <div class="text-muted">Video Link</div>
                <a href="<?php echo $model->getVideoLink() ?>">
                    Open Video
                </a>
            </div>

            
            <div class="mb-3">
                <div>Video Name</div>
                <?php echo $model->video_name ?>
            </div>       
            <?= $form->field($model, attribute: 'status')->dropDownList($model->getStatusLabels()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
