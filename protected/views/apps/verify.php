<?php
/* @var $this AppsController */
/* @var $transaction UserTransactions */
/* @var $app Apps */
/* @var $user Users */
/* @var $price string */
/* @var $transactionResult boolean */
?>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buy-box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">جزئیات خرید</h3>
        </div>
        <div class="panel-body step-content">
            <div class="container-fluid buy-form">
                <?php $this->renderPartial('//layouts/_flashMessage');?>
                <?php if($transactionResult):?>
                    <h4>اطلاعات برنامه</h4>
                    <p><span class="buy-label">عنوان برنامه</span><span class="buy-value"><a><?php echo CHtml::encode($app->title);?></a></span></p>
                    <p><span class="buy-label">قیمت</span><span class="buy-value"><?php echo CHtml::encode(number_format($app->hasDiscount() ? $app->offPrice : $app->price, 0));?> تومان</span></p>
                    <p><span class="buy-label">کد رهگیری تراکنش</span><span class="buy-value"><?php echo CHtml::encode($transaction->token);?></span></p>
                    <p><span class="buy-label">تاریخ</span><span class="buy-value"><?php echo JalaliDate::date('d F Y - H:i', $transaction->date);?></span></p>
                    <a href="<?php echo $this->createUrl('/apps/download/' . CHtml::encode($app->id) . '/' . CHtml::encode($app->title));?>" class="btn btn-success pull-left">دانلود برنامه</a>
                <?php else:?>
                    <a href="<?php echo $this->createUrl('/apps/buy', array('id'=>$app->id, 'title'=>$app->title));?>" class="btn btn-danger">بازگشت به صفحه خرید</a>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
