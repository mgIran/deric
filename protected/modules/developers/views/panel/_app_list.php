<?php
/* @var $data Apps */
Yii::import("comments.models.*");
?>

<div class="tr">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5"><a target="_blank" href="<?= $this->createUrl('/apps/'.$data->id.'/'.urlencode($data->title)) ?>"><?php echo $data->title;?></a></div>
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"><?php echo ($data->status=='enable')?'فعال':'غیر فعال';?></div>
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"><?php
        if($data->price==0)
            echo 'رایگان';
        elseif($data->price==-1)
            echo 'پرداخت درون برنامه';
        else
            Controller::parseNumbers(number_format($data->price,0)).' تومان';
        ?></div>
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"><?= Controller::parseNumbers(number_format($data->install)) ?></div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
        <span style="font-size: 16px;">
            <a style="display: block" class="icon-comment <?= $data->getCountNewComment()?"text-success":"text-hide" ?>" href="<?= $this->createUrl('/apps/comments/'.$data->id) ?>"><?= $data->getCountNewComment()?></a>
        </span>
        <span style="margin-right: 6px;font-size: 17px;">
            <a class="icon-pencil text-info" href="<?php echo $this->createUrl('/developers/apps/update/'.$data->id);?>"></a>
        </span>
        <span style="font-size: 16px">
            <a class="icon-trash text-danger" href="<?php echo $this->createUrl('/developers/apps/delete/'.$data->id);?>" onclick="if(confirm('آیا از حذف این برنامه اطمینان دارید؟')) window.location ='<?= $this->createUrl('/apps/'.$data->id.'/'.urlencode($data->title)) ?>';"></a>
        </span>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4"><span class="label <?php if($data->confirm=='accepted')echo 'label-success';elseif($data->confirm=='refused' or $data->confirm=='change_required')echo 'label-danger';elseif($data->confirm=='incomplete')echo 'label-warning';else echo 'label-info';?>"><?php echo $data->confirmLabels[$data->confirm];?></span></div>
</div>