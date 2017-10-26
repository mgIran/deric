<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'مدیریت کاربران'=>array('admin'),
);

$this->menu=array(
	array('label'=>'لیست کاربران', 'url'=>array('admin')),
);
?>

<div class="box box-primary">
	<div class="box-header with-border"><h3 class="box-title">تغییر وضعیت کاربر <?= $model->email ?></h3></div>
	<div class="box-body">
		<? $this->renderPartial('//layouts/_flashMessage'); ?>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'users-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<?php echo $form->errorSummary($model); ?>

		<div class="form-group">
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',$model->statusLabels,array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>

		<div class="form-group buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class' => 'btn btn-success')); ?>
		</div>

		<?php $this->endWidget(); ?>
	</div>
</div>