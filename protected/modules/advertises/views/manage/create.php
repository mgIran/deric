<?php
/* @var $this ManageController */
/* @var $model Advertises */
/* @var $cover array */

$this->breadcrumbs=array(
	'لیست تبلیغات'=>array('admin'),
	'افزودن تبلیغ',
);
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">افزودن تبلیغ</h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_form', array('model'=>$model, 'cover'=>$cover)); ?>
	</div>
</div>
