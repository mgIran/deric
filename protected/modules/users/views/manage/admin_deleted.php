<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $topUser CActiveDataProvider */
/* @var $topDeveloper CActiveDataProvider */

$this->breadcrumbs=array(
    'کاربران'=>array('/users/manage'),
    'مدیریت کاربران حذف شده',
);
?>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">مدیریت کاربران حذف شده</h3></div>
    <div class="box-body">
        <? $this->renderPartial('//layouts/_flashMessage'); ?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'admins-grid',
                'dataProvider'=>$model->search(true),
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
                        'value' => '$data->statusLabels[$data->status]',
                        'filter' => CHtml::activeDropDownList($model,'statusFilter',$model->statusLabels,array('prompt' => 'همه'))
                    ),
                    array(
                        'header' => 'نوع کاربری',
                        'value' => '$data->role->name',
                        'filter' => CHtml::activeDropDownList($model,'roleId',array('1'=>'کاربر معمولی', '2'=>'توسعه دهنده'),array('prompt' => 'همه'))
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template' => '{restore} {delete}',
                        'deleteConfirmation' => "آیا از حذف همیشگی این کاربر اطمینان دارید؟\nدر صورت حذف تمامی سوابق خرید کاربر از بین خواهد رفت.",
                        'buttons' => array(
                            'restore' => array(
                                'label' => 'بازیابی کاربر',
                                'url' => 'Yii::app()->createUrl("/users/manage/changeStatus/".$data->id."?status=active&return=admin")',
                                'options' => array('class' => 'btn btn-xs btn-success','style' => 'margin-bottom:10px')
                            ),
                            'delete' => array(
                                'label' => 'حذف برای همیشه',
                                'imageUrl' => false,
                                'options' => array('class' => 'btn btn-xs btn-danger')
                            )
                        )
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>