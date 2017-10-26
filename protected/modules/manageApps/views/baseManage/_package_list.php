<?php
/* @var $data AppPackages*/
?>

<div class="tr">
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6"><?php echo CHtml::encode($data->version);?></div>
    <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"><?php echo number_format(filesize(Yii::getPathOfAlias("webroot") . '/uploads/apps/files/'.$data->app->platform->name.'/'.$data->file_name)/1024/1024, 2).' مگابایت';?></div>
    <div class="col-lg-3 col-md-2 hidden-sm hidden-xs"><?php echo JalaliDate::date('d F Y', $data->create_date);?></div>
    <div class="col-lg-3 col-md-2 hidden-sm hidden-xs"><?php if($data->status=='accepted')echo JalaliDate::date('d F Y', $data->publish_date);else echo '-';?></div>
    <div class="col-lg-2 col-md-2 col-sm-4 hidden-xs">
        <span class="label <?php if($data->status=='accepted')echo 'label-success';elseif($data->status=='refused' || $data->status=='change_required')echo 'label-danger';else echo 'label-info';?>">
            <?php echo CHtml::encode($data->statusLabels[$data->status]);?>
        </span>
    </div>
</div>
