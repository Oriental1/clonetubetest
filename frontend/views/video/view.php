<?php
/**
 * 
 * @var $model \common\models\Video
 * @var $similarVideos \common\models\Video[]
 * 
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<div class="row">
    <div class="col-sm-8">
        <div class="ratio ratio-16x9">
            <video src="<?php echo $model->getVideoLink() ?>" poster="<?php echo $model->getThumbnailLink() ?>"controls></video>
        </div>
        <h6 class="mt-2"><?php echo $model->title ?></h6>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php echo $model->getViews()->count() ?> views • 
                <?php echo Yii::$app->formatter->asDate($model->created_at) ?></p>
            </div>
            <div>
                <?php Pjax::begin() ?>
                    <?php echo $this->render('_buttons',[
                        'model' => $model
                    ]) ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <div>
            <p>
                <?php echo \common\helpers\Html::channelLink($model->createdBy) ?>
            </p>
            <?php echo Html::encode($model->description) ?>
        </div>
    <div class="col-sm-4">

    <!--converting the similarVideos[] to force the php to treat it as a traversible array
    <?php foreach ($similarVideos as $index => $similarVideo): ?>
    <?php endforeach; ?>
    -->
        <?php foreach ($similarVideos as $similarVideo): ?>
            <div class="media">
                <a href="<?php echo Url::to(['/video/view', 'id' => $similarVideo->video_id]) ?>">
                    <div class="ratio ratio-16x9">
                        <video src="<?php echo $similarVideo->getVideoLink() ?>"
                        poster="<?php echo $similarVideo->getThumbnailLink() ?>"></video>
                    </div>
                </a>
                <div class="media-body">
                    <h6 class="mt-0"><?php echo $similarVideo->title ?></h6>
                    <div>
                        <p class="m-0">
                            <?php echo \common\helpers\Html::channelLink($similarVideo->createdBy) ?>
                        </p>
                        <small>
                            <?php echo $similarVideo->getViews()->count() ?> views •
                            <?php echo Yii::$app->formatter->asRelativeTime($similarVideo->created_at) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
