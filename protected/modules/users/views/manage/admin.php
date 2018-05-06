<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $topUser CActiveDataProvider */
/* @var $topDeveloper CActiveDataProvider */

$this->breadcrumbs=array(
    'کاربران'=>array('/users/manage'),
    'مدیریت',
);
?>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">مدیریت کاربران</h3></div>
    <div class="box-body">
        <? $this->renderPartial('//layouts/_flashMessage'); ?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'admins-grid',
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'template' => '{items} {pager}',
                'ajaxUpdate' => true,
                'afterAjaxUpdate' => "function(id, data){
                    $('html, body').animate({
                        scrollTop: ($('#'+id).offset().top-130)
                    },1000);
                }",
                'pager' => array(
                    'header' => '',
                    'firstPageLabel' => '<<',
                    'lastPageLabel' => '>>',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'cssFile' => false,
                    'htmlOptions' => array(
                        'class' => 'pagination pagination-sm',
                    ),
                ),
                'pagerCssClass' => 'blank',
                'columns'=>array(
                    'email',
                    array(
                        'header' => 'نام کامل',
                        'name' => 'userDetails.fa_name',
                        'filter' => CHtml::activeTextField($model,'fa_name')
                    ),
                    array(
                        'header' => 'وضعیت',
                        'name' => 't.status',
                        'value' => '$data->statusLabels[$data->status]',
                        'filter' => $model->statusLabels
                    ),
                    array(
                        'header' => 'نوع کاربری',
                        'value' => '$data->role->name',
                        'filter' => CHtml::activeDropDownList($model,'roleId',array('1'=>'کاربر معمولی', '2'=>'توسعه دهنده'),array('prompt' => 'همه'))
                    ),
//                    array(
//                        'header'=>'امتیاز خرید',
//                        'value' => 'is_null($data->userDetails->score)?"-":$data->userDetails->score',
//                    ),
//                    array(
//                        'header'=>'امتیاز فروش',
//                        'value' => 'is_null($data->userDetails->dev_score)?"-":$data->userDetails->dev_score',
//                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template' => '{view} {update} {delete}'
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
