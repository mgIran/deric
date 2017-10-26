<?php
/* @var $this ManageController */
/* @var $model SpecialAdvertises */
/* @var $cover array */

$this->breadcrumbs=array(
	'لیست تبلیغات'=>array('admin'),
	'افزودن تبلیغات',
);

$this->menu=array(
	array('label'=>'لیست تبلیغات', 'url'=>array('admin')),
);
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">افزودن تبلیغ ویژه</h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_form_special', array('model'=>$model, 'cover'=>$cover)); ?>
	</div>
</div>