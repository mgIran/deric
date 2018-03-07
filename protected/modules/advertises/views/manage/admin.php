<?php
/* @var $this ManageController */
/* @var $model AppAdvertises */
/* @var $specialModel AppAdvertises */
$this->breadcrumbs=array(
	'لیست تبلیغات',
);

$this->menu=array(
	array('label'=>'لیست تبلیغات', 'url'=>array('admin')),
);
?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">لیست تبلیغات <?= $model->typeLabels[$model->type] ?></h3>
        <?php if($model->type == AppAdvertises::COMMON_ADVERTISE): ?>
    		<a href="<?php echo $this->createUrl('create?platform_id=1')?>" class="btn btn-default btn-sm">افزودن تبلیغ اندروید</a>
    		<a href="<?php echo $this->createUrl('create?platform_id=2')?>" class="btn btn-default btn-sm">افزودن تبلیغ آی او اس</a>
        <?php elseif($model->type == AppAdvertises::SPECIAL_ADVERTISE): ?>
            <a href="<?php echo $this->createUrl('createSpecial?platform_id=1')?>" class="btn btn-default btn-sm">افزودن تبلیغ ویژه اندروید</a>
            <a href="<?php echo $this->createUrl('createSpecial?platform_id=2')?>" class="btn btn-default btn-sm">افزودن تبلیغ ویژه آی او اس</a>
        <?php elseif($model->type == AppAdvertises::IN_APP_ADVERTISE): ?>
            <a href="<?php echo $this->createUrl('createInApp?platform_id=1')?>" class="btn btn-default btn-sm">افزودن تبلیغ داخل برنامه اندروید</a>
            <a href="<?php echo $this->createUrl('createInApp?platform_id=2')?>" class="btn btn-default btn-sm">افزودن تبلیغ داخل برنامه آی او اس</a>
        <?php endif; ?>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
                'orderField' => 'order',
                'idField' => 'id',
                'orderUrl' => 'order',
				'id'=>'advertises-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'title',
					array(
						'name' => 'platform_id',
						'value' => '$data->platform->title',
						'filter' => CHtml::listData(AppPlatforms::model()->findAll(), 'id', 'title')
					),
					array(
						'name' => 'status',
						'value' => '$data->statusLabels[$data->status]',
						'filter' => $model->statusLabels
					),
					array(
						'name' => 'create_date',
						'value' => 'JalaliDate::date("Y/m/d - H:i",$data->create_date)',
						'filter' => false
					),
					array(
						'class'=>'CButtonColumn',
						'template' => '{update} {delete}',
					),
				),
			)); ?>
		</div>
	</div>
</div>