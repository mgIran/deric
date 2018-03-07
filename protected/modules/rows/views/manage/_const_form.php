<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */
/* @var $form CActiveForm */

$fields = array();
if($model->query)
	$fields = CJSON::decode($model->query);

?>

<div class="form">
	<? $this->renderPartial('//layouts/_flashMessage'); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rows-homepage-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php
		if(!$model->isNewRecord)
			echo CHtml::textField('title',$model->title,array('readOnly' => true,'disabled' => true,'size'=>50,'maxlength'=>50, 'class' => 'form-control'));
		else
			echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, 'class' => 'form-control'));
		?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',$model->statusLabels, array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	<?php
	if($model->isNewRecord):?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'query'); ?>
		<?php echo $form->dropDownList($model,'query',$model->queryList, array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'query'); ?>
	</div>
	<?php
	endif;
	?>
	<div class="form-group hidden">
		<div class="description">فیلدهای کوئری را تنظیم کنید.</div>
		<div class="form-group">
			<?php
			echo CHtml::label('condition','condition');
			echo CHtml::textField('Criteria[condition]',$fields && $fields['condition']?$fields['condition']:'',array('class'=> 'ltr text-left' ,'size' => 70,'placeholder' => 'example: t.id = :id AND t.title LIKE :title'));
			?>
		</div>
		<div class="form-group">
			<?php
			echo CHtml::label('params','params');
			echo CHtml::textField('Criteria[params]',$fields && $fields['params']?$fields['params']:'',array('class'=> 'ltr text-left' ,'size' => 70,'placeholder' => 'example: :id=1#:title="%title%"'));
			?>
		</div>
		<div class="form-group">
			<?php
			echo CHtml::label('order','order');
			echo CHtml::textField('Criteria[order]',$fields && $fields['order']?$fields['order']:'',array('class'=> 'ltr text-left form-control','size' => 70,'placeholder' => 'example: t.id DESC'));
			?>
		</div>
		<div class="form-group">
			<?php
			echo CHtml::label('limit','limit');
			echo CHtml::textField('Criteria[limit]',$fields && $fields['limit']?$fields['limit']:'',array('class'=> 'form-control ltr text-left' ,'size' => 70,'placeholder' => 'example: 10'));
			?>
		</div>
	</div>
	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->