<?php
/* @var $this AppsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $title String */
/* @var $pageTitle String */
?>

<div class="game-title game-title-gallery">
    <div class="game-title-to">
        <h4><b>عبارت مورد نظر: <?= $_GET['term'] ?></b></h4>
    </div>
</div>
<div class="game game-gallery">
    <div class="imgs img-gallery">
        <div class="gallery">
            <?php if($dataProvider->totalItemCount): ?>
                <?php foreach ($dataProvider->getData() as $data): $this->renderPartial('//site/_app_item', compact('data')); endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>