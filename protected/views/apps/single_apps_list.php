<?php
/* @var $this AppsController */
/* @var $dataProvider Apps[] */
/* @var $title String */
/* @var $pageTitle String */
?>

<div class="game-title game-title-gallery">
    <div class="game-title-to">
        <h4><b><?= $title ?></b></h4>
    </div>
</div>
<div class="game game-gallery">
    <div class="imgs img-gallery">
        <div class="gallery">
            <?php if($dataProvider): ?>
                <?php foreach ($dataProvider as $data): $this->renderPartial('//site/_app_item', compact('data')); endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>