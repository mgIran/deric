<?php
/* @var $this AppsController */
/* @var $buy AppBuys */
?>

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buy-box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">جزئیات خرید</h3>
        </div>
        <div class="panel-body step-content">
            <div class="container-fluid buy-form">
                <?php $this->renderPartial('//layouts/_flashMessage');?>
                <h4>اطلاعات برنامه</h4>
                <p><span class="buy-label">عنوان برنامه</span><span class="buy-value"><a><?php echo CHtml::encode($buy->app->title);?></a></span></p>
                <p><span class="buy-label">قیمت</span><span class="buy-value"><?php echo CHtml::encode(number_format($buy->app->hasDiscount() ? $buy->app->offPrice : $buy->app->price, 0));?> تومان</span></p>
                <p><span class="buy-label">تاریخ</span><span class="buy-value"><?php echo JalaliDate::date('d F Y - H:i', $buy->date);?></span></p>
                <a href="<?php echo $this->createUrl('/apps/download/' . CHtml::encode($buy->app->id) . '/' . CHtml::encode($buy->app->title));?>" class="btn btn-success pull-left">دانلود برنامه</a>
            </div>
        </div>
    </div>
</div>
