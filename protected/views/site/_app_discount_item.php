<?php
/* @var $data AppDiscounts */
$app = $data->app;
if($app && $app->hasDiscount()) {
    ?>

    <div class="app-item <?=$app->hasDiscount()?'discount':''?>">
        <div class="app-item-content">
            <div class="pic">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('/apps/'.$app->id.'/'.urlencode($app->lastPackage->package_name)); ?>">
                        <img src="<?php echo Yii::app()->baseUrl.'/uploads/apps/icons/'.CHtml::encode($app->icon); ?>">
                    </a>
                </div>
            </div>
            <div class="detail">
                <div class="app-title">
                    <a href="<?php echo Yii::app()->createUrl('/apps/'.$app->id.'/'.urlencode($app->lastPackage->package_name)); ?>">
                        <?php echo CHtml::encode($app->title); ?>
                        <span class="paragraph-end"></span>
                    </a>
                </div>
                <div class="app-any">
                    <span class="app-price">
                        <?
                        if($app->hasDiscount()):
                            ?>
                            <span class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($app->price, 0)).' تومان'; ?></span>
                            <span ><?= Controller::parseNumbers(number_format($app->offPrice, 0)).' تومان' ; ?></span>
                            <?
                        else:
                            ?>
                            <span ><?= $app->price?Controller::parseNumbers(number_format($app->price, 0)).' تومان':'رایگان'; ?></span>
                            <?
                        endif;
                        ?>
                    </span>
                    <span class="app-rate">
                        <?= Controller::printRateStars($app->rate); ?>
                    </span>
                </div>
            </div>
            <div class="countdown-item" data-time="<?php echo date('Y/m/d H:i:s', $data->end_date);?>"></div>
        </div>
    </div>
    <?php Yii::app()->clientScript->registerScript('countdown', "
        $('.countdown-item').each(function(){
            $(this).countdown($(this).data('time'), function(event) {
                if(event.offset.days==0)
                    $(this).html(event.strftime('%H:%M:%S باقیمانده'));
                else
                    $(this).html(event.strftime('%D روز و %H:%M:%S باقیمانده'));
            });
        });
    ");?>
<?php }?>