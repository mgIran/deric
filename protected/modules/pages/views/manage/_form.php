<?php
/* @var $this PagesManageController */
/* @var $model Pages */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<? $this->renderPartial('//layouts/_flashMessage'); ?>

    <?
    if($this->categorySlug == 'free' || $this->categorySlug == 'document'):
    ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    <?
    endif;
    ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'summary'); ?>
        <?
        $this->widget('ext.ckeditor.CKEditor', array(
            'model'=>$model,
            'attribute'=>'summary',
        ));
        ?>
		<?php echo $form->error($model,'summary'); ?>
	</div>


	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>