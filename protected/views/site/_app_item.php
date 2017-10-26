<?php
/* @var $data Apps */
?>

<div class="app-item <?=$data->hasDiscount()?'discount':''?>">
    <div class="app-item-content">
        <div class="pic">
            <div>
                <a href="<?php echo Yii::app()->createUrl('/apps/'.$data->id.'/'.urlencode($data->lastPackage->package_name));?>">
                    <img src="<?php echo Yii::app()->baseUrl.'/uploads/apps/icons/'.CHtml::encode($data->icon);?>">
                </a>
            </div>
        </div>
        <div class="detail">
            <div class="app-title">
                <a href="<?php echo Yii::app()->createUrl('/apps/'.$data->id.'/'.urlencode($data->lastPackage->package_name));?>">
                    <?php echo CHtml::encode($data->title);?>
                    <span class="paragraph-end"></span>
                </a>
            </div>
            <div class="app-any">
                <span class="app-price">
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
                </span>
                <span class="app-rate">
                    <?= Controller::printRateStars($data->rate); ?>
                </span>
            </div>
        </div>
    </div>
</div>