<?php
/* @var $this ImagesManageController */
/* @var $model AppImages */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'لیست AppImages', 'url'=>array('index')),
	array('label'=>'افزودن AppImages', 'url'=>array('create')),
	array('label'=>'ویرایش AppImages', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف AppImages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت AppImages', 'url'=>array('admin')),
);
?>

<h1>نمایش AppImages #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'app_id',
		'image',
	),
)); ?>
