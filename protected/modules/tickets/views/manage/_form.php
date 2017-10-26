<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */
/* @var $form CActiveForm */
?>

<div class="form">
	<div class="row">
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h4>ارسال تیکت جدید</h4>
		</div>
		<?
		if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
		?>
			<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<a class="btn btn-info pull-left" href="#" onclick="window.history.back();" >بازگشت</a>
			</div>
		<?
		endif;
		?>
	</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tickets-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php echo $form->labelEx($model,'subject'); ?>
			<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'subject'); ?>
		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php echo $form->labelEx($model,'department_id'); ?>
			<?php echo $form->dropDownList($model,'department_id',CHtml::listData(TicketDepartments::model()->findAll(),'id','title'),array('maxlength'=>10,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'department_id'); ?>
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php echo $form->labelEx($model,'text'); ?>
			<?php echo $form->textArea($model,'text',array('rows'=>15,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'text'); ?>
		</div>
		<div id="file-uploader-box" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse">
			<?= CHtml::label('فایل' ,'uploaderImages' ,array('class' => 'control-label')); ?>
			<?php
			$this->widget('ext.dropZoneUploader.dropZoneUploader', array(
				'id' => 'uploaderImages',
				'model' => $model,
				'name' => 'attachment',
				'maxFiles' => 1,
				'maxFileSize' => 2, //MB
				'url' => $this->createUrl('/tickets/manage/upload'),
				'deleteUrl' => $this->createUrl('/tickets/manage/deleteUploaded'),
				'acceptedFiles' => '.jpg, .jpeg, .png, .pdf, .doc, .docx, .zip',
				'serverFiles' => array(),
				//				'data' => array('app_id'=>$model->id),
				'onSuccess' => '
					var responseObj = JSON.parse(res);
					if(responseObj.state == "ok")
					{
						{serverName} = responseObj.fileName;
						$(".submit-image-warning").addClass("hidden");
					}else if(responseObj.state == "error"){
						console.log(responseObj.msg);
					}
            ',
			));
			?>
			<?php echo $form->error($model,'attachment'); ?>
		</div>
	</div>
	<div class="form-group buttons">
		<?php echo CHtml::button('فایل ضمیمه',array('class' => 'btn btn-danger pull-right' ,'data-toggle' => 'collapse' ,'data-target' => '#file-uploader-box')); ?>
		<?php echo CHtml::submitButton('ارسال',array('class' => 'btn btn-success pull-left')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->