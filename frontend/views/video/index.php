<?php
/**
 * 
 * @var $dataProvider \yii\data\ActiveDataProvider
 * 
 */

use PhpParser\Node\Expr\AssignOp\Div;
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

