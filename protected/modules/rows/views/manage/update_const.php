<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های ثابت'=>array('const'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
    array('label'=>'مدیریت', 'url'=>array('const')),
);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">ویرایش ردیف <?php echo $model->title; ?></h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('_const_form', array('model'=>$model)); ?>
    </div>
</div>