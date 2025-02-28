<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;


$this->beginContent(viewFile: '@frontend/views/layouts/base.php')
?>
<main class="d-flex">
    <?php echo $this->render(view: '_sidebar') ?>
        
    <div class="content-wrapper p-3">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<?php $this->endContent() ?>