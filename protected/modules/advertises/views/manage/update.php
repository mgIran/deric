<?php
/* @var $this ManageController */
/* @var $model AppAdvertises */
/* @var $cover array */

$this->breadcrumbs=array(
	'لیست تبلیغات'=>array('admin'),
	'ویرایش',
);
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">ویرایش تبلیغ <?php echo $model->typeLabels[$model->type] ?> <?php
            if($model->platform_id == 1)
                echo 'اندروید';
            if($model->platform_id == 2)
                echo 'آی او اس';
            ?></h3>
	</div>
	<div class="box-body">
		<?php $this->renderPartial('_new_form', array('model'=>$model, 'cover'=>$cover)); ?>
	</div>
</div>