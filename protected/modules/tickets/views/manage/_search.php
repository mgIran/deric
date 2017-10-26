<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */
/* @var $form CActiveForm */
if(isset($_GET['Tickets']))
	$model->attributes = $_GET['Tickets'];
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'method'=>'get',
)); ?>

	<div class="form-group">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>10,'maxlength'=>10,'class' => 'form-control')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('class' => 'form-control')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'department_id'); ?>
		<?php echo $form->dropDownList($model,'department_id',CHtml::listData(TicketDepartments::model()->findAll(),'id','title'),array('prompt' => 'همه','class' => 'form-control')); ?>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',array(
			'' => 'همه',
			'waiting' => $model->statusLabels['waiting'],
			'pending' => $model->statusLabels['pending'],
			'open' => $model->statusLabels['open'],
			'close' => $model->statusLabels['close'],
		),array('class' => 'form-control')); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton('جستجو',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>