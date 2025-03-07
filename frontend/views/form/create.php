<?php

use yii\helpers\Url;
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = 'Address Book';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?php echo Html::a('<i class="fas fa-eye"></i> List', Url::to(['form/index']), [
            'class' => 'btn btn-primary',
            'style' => 'font-weight: bold; padding: 10px 20px; border-radius: 8px;'
        ]) ?>
    </p>

</div>


<div class="person-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="card card-primary">
        <div class="card-header bg-primary text-white">
            <h4>Person Name</h4>
        </div>
        <div class="card-body">
            <?= $form->field($person, 'first_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($person, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <br>

    <div class="card card-secondary">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.item',
            'limit' => 3, // max addresses
            'min' => 1, // min addresses required
            'insertButton' => '.add-item',
            'deleteButton' => '.remove-item',
            'model' => $addresses[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'city', 
                'state', 
                'postal_code'
            ],
        ]); ?>
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h4>Address Details</h4>
            <button type="button" class="add-item btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add Address
            </button>
        </div>
        <div class="card-body">

            <div class="container-items">
                <?php foreach ($addresses as $i => $address): ?>
                    <div class="item card border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($address, "[{$i}]city")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($address, "[{$i}]state")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($address, "[{$i}]postal_code")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="remove-item btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    

    <?php ActiveForm::end(); ?>
</div>
