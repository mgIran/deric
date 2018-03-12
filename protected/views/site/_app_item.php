<?php
/* @var $data Apps */
?>
<div class="games-item <?=$data->hasDiscount()?'discount':''?>">
    <div class="thumb"><a href="<?= $data->getViewUrl() ?>"><img src="<?php echo Yii::app()->getBaseUrl(true).'/uploads/apps/icons/'.CHtml::encode($data->icon);?>" alt="<?= CHtml::encode($data->title) ?>"></a></div>
    <div class="text">
        <h5 class="title"><a href="<?= $data->getViewUrl() ?>"><?= CHtml::encode($data->title) ?><span class="paragraph-end"></span></a></h5>
        <div class="free">
            <?php if($data->price==0):?>
                <a href="<?php echo Yii::app()->createUrl('/apps/free')?>">رایگان</a>
            <?php else:?>
                <?
                if($data->hasDiscount()):
                    ?>
                    <span class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($data->price, 0)).' تومان'; ?></span>
                    <span ><?= Controller::parseNumbers(number_format($data->offPrice, 0)).' تومان' ; ?></span>
                <?
                else:
                    ?>
                    <span ><?= $data->price?Controller::parseNumbers(number_format($data->price, 0)).' تومان':'رایگان'; ?></span>
                <?
                endif;
                ?>
            <?php endif;?>
        </div>
        <div class="star">
            <?= Controller::printRateStars($data->getRate()) ?>
        </div>
    </div>
</div>