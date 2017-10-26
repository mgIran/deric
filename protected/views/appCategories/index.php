<?php
/* @var $this AppCategoriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'App Categories',
);

$this->menu=array(
	array('label'=>'Create AppCategories', 'url'=>array('create')),
	array('label'=>'Manage AppCategories', 'url'=>array('admin')),
);
?>

<h1>App Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
