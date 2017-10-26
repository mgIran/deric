<?php
/* @var $this UsersManageController */
/* @var $model Users */

$this->breadcrumbs=array(
	'مدیریت کاربران'=>array('admin'),
	'افزودن',
);
?>

<div class="box box-primary">
	<div class="box-header with-border"><h3 class="box-title">افزودن کاربر</h3></div>
	<div class="box-body">
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
		