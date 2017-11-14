<?php
/* @var $data Apps*/
?>

<div class="col-md-6 app-item">
    <?php echo CHtml::radioButton('app_id', isset($_POST['app_id']) && $_POST['app_id'] == $data->id?:false, array(
        'value'=>$data->id,
    ));?>
    <img src="<?php echo Yii::app()->baseUrl.'/uploads/apps/icons/'.CHtml::encode($data->icon);?>">
    <h5><?php echo CHtml::encode($data->title);?></h5>
    <small><?php echo CHtml::encode($data->category->title);?></small>
</div>
