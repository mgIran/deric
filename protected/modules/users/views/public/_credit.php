<?php
/* @var $this PublicController */
/* @var $model Users */
?>
<div class="db-to-1">
    <h4><b>اعتبار</b></h4>
    <span>اعتبار فعلی شما :<?php echo Controller::parseNumbers(number_format($model->userDetails->credit, 0));?> تومان</span>
    <a href="<?php echo $this->createUrl('/users/credit/buy');?>" class="btn btn-primary">خرید اعتبار</a>
</div>
<div class="db-to-2">
    <h4><b>امتیاز</b></h4>
    <span>امتیاز شما: <?php echo Controller::parseNumbers(number_format($model->userDetails->score, 0));?></span>
    <span>به ازای خرید هر برنامه یک امتیاز در نظر گرفته می شود.</span>
</div>