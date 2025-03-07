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

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a('<i class="fas fa-list"></i> List', Url::to(['form/index']), [
            'class' => 'btn btn-primary fw-bold px-4 py-2 rounded',
        ]) ?>
        <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $person->id], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => 'Are you sure you want to delete this person?',
                    'data-method' => 'post',
                ]) ?>
    </p>
</div>

<div class="person-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <!-- Person Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Person Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($person, 'first_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($person, 'last_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Details -->
    <div class="card shadow-sm">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.item',
            'limit' => 3, // Maximum addresses
            'min' => 0, // Minimum addresses required
            'insertButton' => '.add-item',
            'deleteButton' => '.remove-item',
            'model' => $addresses[0],
            'formId' => 'dynamic-form',
            'formFields' => ['city', 'state', 'postal_code'],
        ]); ?>

        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"> Address Details</h5>
            <button type="button" class="add-item btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add Address
            </button>
        </div>

        <div class="card-body">
            <div class="container-items">
                <?php foreach ($addresses as $i => $address): ?>
                    <div class="item card border-0 shadow-sm p-3 mb-3">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <?= $form->field($address, "[{$i}]city")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($address, "[{$i}]state")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($address, "[{$i}]postal_code")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="remove-item btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php DynamicFormWidget::end(); ?>
    </div>

    <br>

    <div class="text-center">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary px-4 py-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
