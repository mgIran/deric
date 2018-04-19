<?php
/** @var $this Controller */
?>
<div class="buttons">
    <div class="buttons-container">
        <div class="is-carousel owl-theme" data-dots="0" data-nav="0" data-items="5" data-autoplay="0" data-mouseDrag="1" data-responsive='{"1200":{"items":"5"},"768":{"items":"4", "dots":true},"500":{"items":"2", "dots":true},"0":{"items":"1", "dots":true}}'>
            <?php foreach ($this->quickAccesses as $quickAccess):?>
            <div class="item one">
                <a class="item-link" href="<?= Yii::app()->createUrl($quickAccess['url']) ?>"><?= $quickAccess['label'] ?></a>
                <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/quickAccess/'.$quickAccess['img']?>" alt="">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>