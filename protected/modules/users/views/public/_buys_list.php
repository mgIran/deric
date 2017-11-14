<?php
/* @var $this PublicController */
/* @var $model Users*/
?>

<div class="container-fluid">
    <?php if(empty($model->appBuys)):?>
        نتیجه ای یافت نشد.
    <?php else:?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>نام برنامه</th>
                    <th>مبلغ برنامه</th>
                    <th>مقدار تخفیف</th>
                    <th>مبلغ پرداخت شده</th>
                    <th>زمان</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model->appBuys as $buy):?>
                    <tr>
                        <td>
                            <a href="<?= $buy->app->getViewUrl() ?>">
                                <?php echo CHtml::encode($buy->app->title);?>
                            </a>
                        </td>
                        <td><?php echo Controller::parseNumbers(number_format($buy->app_price)).' تومان';?></td>
                        <td><?php echo Controller::parseNumbers(number_format($buy->discount_amount)).' تومان';?></td>
                        <td class="text-info"><?php echo Controller::parseNumbers(number_format($buy->pay_amount)).' تومان';?></td>
                        <td><?php echo JalaliDate::date('d F Y - H:i', $buy->date);?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php endif;?>
</div>