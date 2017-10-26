<?php
/* @var $data AppDiscounts */

?>

<div class="tr">
    <div class="col-lg-3 col-md3 col-sm-3 col-xs-4"><a target="_blank" href="<?= $this->createUrl('/apps/'.$data->app->id.'/'.urlencode($data->app->title)) ?>"><?php echo $data->app->title;?></a></div>
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"><?php echo ($data->app->status=='enable')?'فعال':'غیر فعال';?></div>
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"><?php echo ($data->app->price==0)?'رایگان':Controller::parseNumbers(number_format($data->app->price,0)).' تومان';?></div>
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"><?= Controller::parseNumbers($data->percent).'%' ?></div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><?= Controller::parseNumbers(number_format($data->offPrice)).' تومان' ?></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
        <?
        echo Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->start_date));
        echo '<br>الی<br>';
        echo Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->end_date));
        ?>
    </div>
</div>