<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;



/** @var yii\web\View $this */

$this->title = 'Dynamic Form';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?php echo Html::a('<i class="fas fa-plus"></i> Create', Url::to(['form/create']), [
            'class' => 'btn btn-primary',
            'style' => 'font-weight: bold; padding: 10px 20px; border-radius: 8px;'
        ]) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], // Auto numbering
            // Searchable fields
            [
                'attribute' => 'first_name',
                'filter' => Html::activeTextInput($searchModel, 'first_name', ['class' => 'form-control']),
            ],
            [
                'attribute' => 'last_name',
                'filter' => Html::activeTextInput($searchModel, 'last_name', ['class' => 'form-control']),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'text-align: right;'],
                'contentOptions' => ['class' => 'text-right'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-secondary btn-sm']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data-confirm' => 'Are you sure you want to delete this item?',
                            'data-method' => 'post',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
