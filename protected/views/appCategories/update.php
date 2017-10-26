<?php
/* @var $this AppCategoriesController */
/* @var $model AppCategories */

$this->breadcrumbs=array(
	'دسته بندی های برنامه' => array('admin'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن دسته بندی', 'url'=>array('create')),
	array('label'=>'مدیریت دسته بندی ها', 'url'=>array('admin')),
);
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">ویرایش دسته بندی <?php echo $model->title; ?></h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>