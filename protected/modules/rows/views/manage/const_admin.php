<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های ثابت',
);
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">مدیریت ردیف های ثابت</h3>
    </div>
    <div class="box-body">
        <? $this->renderPartial('//layouts/_flashMessage'); ?>
        <p class="description">** می توانید با جابجا کردن سطرها ردیف ها را مرتب سازی کنید.</p>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'rows-homepage-grid',
                'dataProvider'=>$model->search(true),
                'filter'=>$model,
                'itemsCssClass'=>'table table-striped',
                'columns'=>array(
                    'title',
                    array(
                        'name' => 'status',
                        'value' => '$data->statusLabel',
                        'filter' => $model->statusLabels
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template' => '{update}',
                        'buttons' => array(
                            'update' => array(
                                'url' => 'Yii::app()->createUrl("/rows/manage/updateConst",array("id" => $data->id))'
                            )
                        )
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
