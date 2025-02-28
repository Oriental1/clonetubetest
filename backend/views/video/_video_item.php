<?php
/**
 * @var $model \common\models\Video
 * 
 */

use yii\helpers\StringHelper;
//use yii\helpers\Urls;

?>

<div class="d-flex">
    <a href="<?php echo yii\helpers\Url::to(['/video/update', 'video_id' => $model->video_id]) ?>">
    <div class="ratio ratio-16x9 me-2" style="width: 120px;">
        <video src="<?php echo $model->getVideoLink() ?>" 
               poster="<?php echo $model->getThumbnailLink() ?>" 
               class="w-100">
        </video>
    </div></a>
    <div>
        <h6 class="mt-0"><?php echo $model->title ?></h6>
        <p class="mb-0">
            <?php echo StringHelper::truncateWords($model->description, 10) ?>
        </p>
    </div>
</div>
