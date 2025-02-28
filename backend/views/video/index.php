<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'content' => function($model){
                    return $this-> render('_video_item', ['model' => $model]);
                }
            ],
            [
                'attribute' => 'status',
                'content' => function($model){
                    return $model->getStatusLabels()[$model->status];
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            //'title',
            //'description:ntext',
            //'tags',
            //'has_thumbnail',
            //'video_name',
            //'created_by',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'video_id' => $model->video_id]);
                },
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="fas fa-trash"></i>', // Icon for delete button
                            $url,
                            [
                                'title' => 'Delete',
                                'data-confirm' => 'Are you sure you want to delete this video?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn btn-danger btn-sm'
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>


</div>
