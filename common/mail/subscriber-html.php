<?php
/**
 * 
 * @var $channel \common\models\User
 * @var $user \common\models\User
 * 
 */
?>

<p>Hello <?php echo $channel->username ?></p>
<p>User <?php echo \common\helpers\Html::channelLink($user, schema: true) ?>
    has subscribe to you</p>

<p>CloneTubeTest team</p>