<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */
/* @var $form CActiveForm */
?>

<div class="form">
	<? $this->renderPartial('//layouts/_flashMessage'); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rows-homepage-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title', array('class' => 'control-label')); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
		<?php echo $form->dropDownList($model,'status',$model->statusLabels, array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class' => 'btn btn-success')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->