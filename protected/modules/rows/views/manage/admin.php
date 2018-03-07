<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های دلخواه برنامه ها',
);

$this->menu=array(
	array('label'=>'افزودن ردیف', 'url'=>array('create')),
);
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">مدیریت ردیف های داینامیک</h3>
        <a href="<?php echo $this->createUrl('create')?>" class="btn btn-default btn-sm">افزودن ردیف</a>
    </div>
    <div class="box-body">
        <? $this->renderPartial('//layouts/_flashMessage'); ?>
        <p class="description">** می توانید با جابجا کردن سطرها ردیف ها را مرتب سازی کنید.</p>
        <div class="table-responsive">
            <?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
                'orderField' => 'order',
                'idField' => 'id',
                'orderUrl' => 'order',
                'id'=>'rows-homepage-grid',
                'dataProvider'=>$model->search(),
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
                        'template' => '{update} {delete}',
                        'buttons' =>array(
                            'delete' =>array(
                                'visible' => '!$data->query'
                            )
                        )
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

