<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $tableName string */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $columns array */

$this->title = "Viewing Table: $tableName";
?>

<h2><?= Html::encode($this->title) ?></h2>

<!-- Home Button -->
<div class="d-flex justify-content-start mb-3">
    <?= Html::a('Home', ['/excel/index'], ['class' => 'btn btn-primary me-2']) ?>
</div>

<!-- Drop Table Button -->
<?= Html::a('Drop Table', ['excel/drop-table', 'tableName' => $tableName], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Are you sure you want to delete this table? This action cannot be undone.',
        'method' => 'post',
    ],
]) ?>

<!-- Table Display -->
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
    'columns' => array_merge(
        array_map(fn($column) => [
            'attribute' => $column,
            'label' => $column,
        ], $columns),
        [
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'text-align: right;'],
                'contentOptions' => ['class' => 'text-right'],
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) use ($tableName) {
                        return Html::a('<i class="fas fa-trash"></span>', ['excel/delete-row'], [
                            'title' => 'Delete',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to delete this row?',
                            'data-params' => [
                                'tableName' => $tableName,
                                'id' => $model['id'], // 'id' is the primary key
                            ],
                            'class' => 'btn btn-danger btn-sm',
                        ]);
                    },
                ],
            ],
        ]
    ),
]); 

?>
