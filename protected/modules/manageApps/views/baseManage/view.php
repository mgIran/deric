<?php
/* @var $this BaseManageController */
/* @var $model Apps */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'لیست Apps', 'url'=>array('index')),
	array('label'=>'افزودن Apps', 'url'=>array('create')),
	array('label'=>'ویرایش Apps', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف Apps', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت Apps', 'url'=>array('admin')),
);
?>

<h1>نمایش Apps #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'developer_id',
		'category_id',
		'status',
		'price',
		'file_name',
		'icon',
		'description',
		'change_log',
		'permissions',
		'size',
		'version',
		'confirm',
	),
)); ?>
