<?php
/* @var $this BaseManageController */
/* @var $model Apps */
/* @var $icon array */
/* @var $tax string */
/* @var $commission string */



$this->breadcrumbs=array(
	'مدیریت'=>Yii::app()->createUrl('/manageApps/'.$this->controller.'/admin'),
	$model->title=>$model->getViewUrl(),
	'ثبت امتیاز فیک',
);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">ثبت امتیاز فیک برای برنامه "<?= $model->title ?>"</h3>
    </div>
    <div class="box-body">
        <? $this->renderPartial('//layouts/_flashMessage'); ?>
        <?php echo CHtml::beginForm(); ?>
        <div class="form-group">
            <label for="">تعداد امتیاز</label>
            <?php echo CHtml::numberField('qty', '1', array('class'=> 'form-control', 'min' => 1, 'max' => 10000)); ?>
        </div>

        <div class="form-group">
            <label for="">امتیاز موردنظر</label>
            <?php echo CHtml::dropDownList('rate', '', [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
            ], array('class'=> 'form-control')); ?>
        </div>

        <div class="form-group buttons">
            <?php echo CHtml::submitButton('ثبت امتیاز',array('class' => 'btn btn-success')); ?>
        </div>

        <?php echo CHtml::endForm() ?>
    </div>
</div>