<?php
/* @var $data AppPackages*/
?>

<div class="tr">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8"><?php echo CHtml::encode($data->package_name);?></div>
    <div class="col-lg-1 col-md-1 col-sm-4 hidden-xs"><?php echo CHtml::encode($data->version);?></div>
    <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"><?php echo Controller::fileSize(Yii::getPathOfAlias("webroot") . '/uploads/apps/files/'.$data->app->platform->name.'/'.$data->file_name);?></div>
    <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"><?php echo JalaliDate::date('d F Y', $data->create_date);?></div>
    <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"><?php if($data->status=='accepted')echo JalaliDate::date('d F Y', $data->publish_date);else echo '-';?></div>
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
        <span class="label <?php if($data->status=='accepted')echo 'label-success';elseif($data->status=='refused' || $data->status=='change_required')echo 'label-danger';else echo 'label-info';?>">
            <?php echo CHtml::encode($data->statusLabels[$data->status]);?>
        </span>
        <?php if($data->status=='refused' or $data->status=='change_required'):?>
            <a class="btn btn-info btn-xs" style="margin-right: 5px;" data-toggle="collapse" data-parent="#packages-list" href="#reason-<?php echo $data->id?>">دلیل</a>
        <?php endif;?>
    </div>
    <?php if($data->status=='refused' or $data->status=='change_required'):?>
        <div id="reason-<?php echo $data->id?>" class="collapse col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="reason-collapse">
                <?php if($data->status=='refused'):?>
                    <p>این بسته به دلایل زیر رد شده است:</p>
                <?php elseif($data->status=='change_required'):?>
                    <p>این بسته نیاز به تغییرات زیر دارد:</p>
                <?php endif;?>
                <?php echo CHtml::encode($data->reason);?>
            </div>
        </div>
    <?php endif;?>
</div>
