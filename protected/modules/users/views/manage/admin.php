<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $topUser CActiveDataProvider */
/* @var $topDeveloper CActiveDataProvider */

$this->breadcrumbs=array(
    'کاربران'=>array('manage'),
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
                'columns'=>array(
                    'email',
                    array(
                        'header' => 'نام کامل',
                        'value' => '$data->userDetails->fa_name',
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
                        'header'=>'امتیاز خرید',
                        'value' => 'is_null($data->userDetails->score)?"-":$data->userDetails->score',
                    ),
                    array(
                        'header'=>'امتیاز فروش',
                        'value' => 'is_null($data->userDetails->dev_score)?"-":$data->userDetails->dev_score',
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template' => '{view} {update} {delete}'
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
<div class="row">
    <section class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">برترین کاربر</h3>&nbsp;<small>(خریدار)</small></div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'best-buyer-grid',
                        'dataProvider'=>$topUser,
                        'template'=>'{items}',
                        'columns'=>array(
                            'email',
                            array(
                                'header' => 'نام کامل',
                                'value' => '$data->userDetails->fa_name',
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
                                'header'=>'امتیاز خرید',
                                'value' => 'is_null($data->userDetails->score)?"-":$data->userDetails->score',
                            ),
                            array(
                                'header'=>'امتیاز فروش',
                                'value' => 'is_null($data->userDetails->dev_score)?"-":$data->userDetails->dev_score',
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{view} {update} {delete}'
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border"><h3 class="box-title">برترین توسعه دهنده</h3>&nbsp;<small>(فروشنده)</small></div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'best-seller-grid',
                        'dataProvider'=>$topDeveloper,
                        'template'=>'{items}',
                        'columns'=>array(
                            'email',
                            array(
                                'header' => 'نام کامل',
                                'value' => '$data->userDetails->fa_name',
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
                                'header'=>'امتیاز خرید',
                                'value' => 'is_null($data->userDetails->score)?"-":$data->userDetails->score',
                            ),
                            array(
                                'header'=>'امتیاز فروش',
                                'value' => 'is_null($data->userDetails->dev_score)?"-":$data->userDetails->dev_score',
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{view} {update} {delete}'
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </section>
</div>
