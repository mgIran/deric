<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">افزودن ردیف</h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>