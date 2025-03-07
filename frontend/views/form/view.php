<?php

use yii\helpers\Html;

$this->title;
$this->params['breadcrumbs'][] = ['label' => 'Persons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="person-view container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
            <?= Html::a('<i class="fas fa-eye"></i> List', ['index'], ['class' => 'btn btn-primary me-2']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Update', ['update', 'id' => $person->id], ['class' => 'btn btn-warning me-2']) ?>
                <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $person->id], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => 'Are you sure you want to delete this person?',
                    'data-method' => 'post',
                ]) ?>
            </div>

            <table class="table table-hover table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light">ID</th>
                        <td><?= Html::encode($person->id) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">First Name</th>
                        <td><?= Html::encode($person->first_name) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Last Name</th>
                        <td><?= Html::encode($person->last_name) ?></td>
                    </tr>
                </tbody>
            </table>

            
            <?php 
            if (!empty($addresses)): ?>
                <div class="mt-4">
                    <h4 class="text-primary">Addresses</h4>
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>City</th>
                                <th>State</th>
                                <th>Postal Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($addresses as $address): ?>
                                <tr>
                                    <td><?= Html::encode($address->city) ?></td>
                                    <td><?= Html::encode($address->state) ?></td>
                                    <td><?= Html::encode($address->postal_code) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
