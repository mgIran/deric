<?php
/* @var $this BaseManageController */
/* @var $model Apps */
/* @var $icon array */
/* @var $tax string */
/* @var $commission string */

$this->breadcrumbs=array(
	'مدیریت'=>Yii::app()->createUrl('/manageApps/'.$this->controller.'/admin'),
	'افزودن',
);
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="pull-right header">افزودن برنامه</li>
        <li class="active"><a data-toggle="tab" href="#info">عمومی</a></li>
<!--        <li class="disabled"><a>بسته</a></li>-->
<!--        <li class="disabled"><a >تصاویر</a></li>-->
    </ul>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <?php $this->renderPartial('manageApps.views.baseManage._form', array(
                'model'=>$model,'icon'=>$icon,
                'tax'=>$tax,
                'commission'=>$commission,
            )); ?>
        </div>
    </div>
</div>