<?php
/* @var $this AppCategoriesController */
/* @var $model AppCategories */

$this->breadcrumbs=array(
	'دسته بندی های برنامه' => array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت دسته بندی ها', 'url'=>array('admin')),
);
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">افزودن دسته بندی</h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>