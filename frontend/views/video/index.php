<?php
/**
 * 
 * @var $dataProvider \yii\data\ActiveDataProvider
 * 
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

?>


<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
    'itemView' => '_video_item',
    'layout' => '<div class="d-flex flex-wrap">{items}</div>{pager}',
    'itemOptions' => [
        'tag' => false
    ]
]) ?>

