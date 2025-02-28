<?php

/** @var $this yii\web\View 
 * @var $latestVideo \common\models\Video
 * @var $numberOfView integer
 * @var $numberOfSubscribers integer
 * @var $subscribers \common\models\Subscriber[]
 */

$this->title = 'My Yii Application';
?>
<div class="site-index d-flex">
    <div class="card m-2" style="width: 14rem;">
        <?php if ($latestVideo): ?>
        <div class="ratio ratio-16x9 mb-3">
            <video src="<?php echo $latestVideo->getVideoLink() ?>"
                poster="<?php echo $latestVideo->getThumbnailLink() ?>"></video>
        </div>

        <h6 class="card-title"><?php echo $latestVideo->title ?></h6>
        <p class="card-text">
            Likes: <?php echo $latestVideo->getLikes()->count() ?> <br>
            Views: <?php echo $latestVideo->getViews()->count() ?>
        </p>
        <a href="<?php echo \yii\helpers\Url::to(['/video/update',
            'id' => $latestVideo->video_id]) ?>" 
            class="btn btn-primary">Edit</a>
    </div>
    <?php else: ?>
        <div>
            You have not uploaded any video yet
        </div>
    <?php endif; ?>
    <div class="card m-2" style="width: 14rem;">
        <h6 class="card-title">Total Views</h6>
        <p class="card-text" style="font-size: 48px">
            <?php echo $numberOfView ?>
        </p>
    </div>
    
    <div class="card m-2" style="width: 14rem;">
        <h6 class="card-title">Total Subscribers</h6>
        <p class="card-text" style="font-size: 48px">
            <?php echo $numberOfSubscribers ?>
        </p>
    </div>
    <div class="card m-2" style="width: 14rem;">
        <div>
            <h6 class="card-title">Latest Subscribers</h6>
            <p class="card-text" style="font-size: 48px">
                <?php foreach ($subscribers as $subscriber): ?>
                    <div>
                        <?php echo $subscriber->user->username ?>
                    </div>
                <?php endforeach; ?>    
            </p>
        </div>
    </div>
</div>
