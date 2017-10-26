<?php
/* @var $this AdminsManageController */
/* @var $model Admins */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'مدیران'=> array('/admins/manage'),
    'مدیریت'=>array('admin'),
    'ویرایش',
);

$this->menu=array(
    array('label'=>'مدیریت مدیران', 'url'=>array('index')),
    array('label'=>'افزودن مدیر', 'url'=>array('create')),
);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">ویرایش مدیر  <?php echo $model->username; ?></h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>