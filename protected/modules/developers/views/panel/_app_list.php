<?php
/* @var $data Apps */
Yii::import("comments.models.*");
?>
<tr>
    <td><a target="_blank" href="<?= $data->getViewUrl() ?>"><?php echo $data->title;?></a></td>
    <td><?php echo ($data->status=='enable')?'فعال':'غیر فعال';?></td>
    <td><?php
        if($data->price==0)
            echo 'رایگان';
        elseif($data->price==-1)
            echo 'پرداخت درون برنامه';
        else
            echo Controller::parseNumbers(number_format($data->price,0)).' تومان';
        ?></td>
    <td>
        <?= Controller::parseNumbers(number_format($data->install)) ?>
    </td>
    <td>
        <span style="font-size: 16px;">
            <a style="display: block" class="icon-comment <?= $data->getCountNewComment()?"text-success":"text-hide" ?>" href="<?= $this->createUrl('/apps/comments/'.$data->id) ?>"><?= $data->getCountNewComment()?></a>
        </span>
        <span style="margin-right: 6px;font-size: 17px;">
            <a class="icon-pencil text-info" href="<?php echo $this->createUrl('/developers/apps/update/'.$data->id);?>"></a>
        </span>
        <span style="font-size: 16px">
            <a class="icon-trash text-danger" href="<?php echo $this->createUrl('/developers/apps/delete/'.$data->id);?>" onclick="if(confirm('آیا از حذف این برنامه اطمینان دارید؟')) window.location ='<?= $data->getViewUrl() ?>';"></a>
        </span>
    </td>
    <td>
        <span class="label <?php if($data->confirm=='accepted')echo 'label-success';elseif($data->confirm=='refused' or $data->confirm=='change_required')echo 'label-danger';elseif($data->confirm=='incomplete')echo 'label-warning';else echo 'label-info';?>"><?php echo $data->confirmLabels[$data->confirm];?></span>
    </td>
</tr>