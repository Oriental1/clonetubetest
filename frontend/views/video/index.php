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


<?php echo Html::a('Dynamic Form', Url::to(['form/index']), 
    ['class' => 'btn btn-primary']) ?>
<?php echo Html::a('eXcel to database', Url::to(['excel/index']), 
    ['class' => 'btn btn-primary']) ?>
