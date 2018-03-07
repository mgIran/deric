<?php
/** @var $this Controller */
?>
<div class="buttons">
    <div class="buttons-container">
        <?php foreach ($this->quickAccesses as $quickAccess):?>
        <div class="item one">
            <a class="item-link" href="<?= Yii::app()->createUrl($quickAccess['url']) ?>"><?= $quickAccess['label'] ?></a>
            <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/quickAccess/'.$quickAccess['img']?>" alt="">
        </div>
        <?php endforeach; ?>
    </div>
</div>