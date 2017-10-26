<?php
/* @var $this ManageController */
/* @var $model Advertises */
/* @var $cover array */

$this->breadcrumbs=array(
	'لیست تبلیغات'=>array('admin'),
	'ویرایش',
);
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">ویرایش تبلیغ <?php echo $model->app->title ?></h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_form', array('model'=>$model, 'cover'=>$cover)); ?>
	</div>
</div>