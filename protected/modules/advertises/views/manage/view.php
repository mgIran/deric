<?php
/* @var $this ManageController */
/* @var $model Advertises */

$this->breadcrumbs=array(
	'Advertises'=>array('index'),
	$model->app_id,
);

$this->menu=array(
	array('label'=>'List Advertises', 'url'=>array('index')),
	array('label'=>'Create Advertises', 'url'=>array('create')),
	array('label'=>'Update Advertises', 'url'=>array('update', 'id'=>$model->app_id)),
	array('label'=>'Delete Advertises', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->app_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Advertises', 'url'=>array('admin')),
);
?>

<h1>View Advertises #<?php echo $model->app_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'app_id',
		'cover',
		'fade_color',
		'status',
		'create_date',
	),
)); ?>
