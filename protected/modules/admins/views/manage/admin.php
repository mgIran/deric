<?php
/* @var $this AdminsManageController */
/* @var $model Admins */

$this->breadcrumbs=array(
	'مدیران'=>array('index'),
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#admins-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">مدیریت مدیران</h3>
		<a href="<?php echo $this->createUrl('create')?>" class="btn btn-default btn-sm">افزودن مدیر</a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'admins-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'username',
					'email',
					array(
						'header' => 'نقش',
						'name' => 'role.name',
						'filter' => CHtml::activeDropDownList($model , 'roleId' ,
							CHtml::listData(AdminRoles::model()->findAll() , 'id' , 'name'))
					),
					array(
						'class'=>'CButtonColumn',
						'template' => '{update} {delete}'
					),
				),
			)); ?>
		</div>
	</div>
</div>