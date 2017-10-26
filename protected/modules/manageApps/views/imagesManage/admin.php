<?php
/* @var $this ImagesManageController */
/* @var $model AppImages */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن AppImages', 'url'=>array('create')),
);
?>

<h1>مدیریت App Images</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'app-images-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'app_id',
		'image',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
