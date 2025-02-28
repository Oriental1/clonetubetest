<?php
/**
 * 
 * 
 * @var $this \yii\web\View
 * @var $channel \common\models\User
 * @var $dataProvider \yii\data\ActiveDataProvider
 * 
 */
use yii\helpers\Url;
use \yii\widgets\ListView;


?>

<div class="container bg-light p-5 rounded">
    <h1 class="display-4"><?php echo htmlspecialchars($channel->username) ?></h1>
    <hr class="my-4">
    <?php \yii\widgets\Pjax::begin() ?>
        <?php echo $this->render('_subscribe', [
            'channel' => $channel
        ]) ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>

<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@frontend/views/video/_video_item',
    'layout' => '<div class="d-flex flex-wrap">{items}</div>{pager}',
    'itemOptions' => [
        'tag' => false
    ]
]) ?>