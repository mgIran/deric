<?php
/* @var $this AppsController */
/* @var $data Apps */
?>

<div class="app-details">
    <div class="pic">
        <img src="<?= Yii::app()->baseUrl.'/uploads/apps/icons/'.CHtml::encode($data->icon); ?>">
    </div>
    <div class="app-content">
        <div class="title">
            <a href="<?php echo $this->createUrl('/apps/'.CHtml::encode($data->id).'/'.CHtml::encode($data->lastPackage->package_name));?>"><?php echo CHtml::encode($data->title);?></a>
        </div>
        <div class="title" >
            <span class="text-right green col-lg-6 col-md-6 col-sm-6 col-xs-6" >
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
            <span class="ltr text-left app-rate col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left" >
                <?= Controller::printRateStars($data->rate); ?>
            </span>
        </div>
        <div class="app-desc">
            <?php
                echo strip_tags(nl2br($data->description));
            ?>
            <span class="paragraph-end"></span>
        </div>
    </div>
    <div class="app-footer">
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs"><?php echo Controller::parseNumbers(number_format($data->install, 0)).' دانلود';?></span>
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs"><?php echo Controller::parseNumbers(round($data->size/1024,1)).' کیلوبایت';?></span>
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs green"><?php echo (is_null($data->developer_id) or empty($data->developer_id))?CHtml::encode($data->developer_team):CHtml::encode($data->developer->userDetails->fa_name);?></span>
    </div>
</div>