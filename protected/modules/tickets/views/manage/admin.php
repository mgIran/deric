<?php
/* @var $this AdminsManageController */
/* @var $dataProvider Admins */

$this->breadcrumbs=array(
		'پشتیبانی',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#tickets-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});

setInterval(function(){
	$.fn.yiiGridView.update('tickets-grid');
}, 15000);

");
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">پشتیبانی</h3>
	</div>
	<div class="box-body">
		<div class="well">
			<?php $this->renderPartial("_search",array('model' => new Tickets())); ?>
		</div>
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'tickets-grid',
					'dataProvider'=>$dataProvider,
					'rowCssClassExpression' => '$data->getCssClass()',
					'columns'=>array(
						array(
							'value' => function($data){
								$criteria = new CDbCriteria();
								$criteria->compare('visit',0);
								$criteria->compare('ticket_id',$data->id);
								$criteria->compare('sender','user');
								return TicketMessages::model()->count($criteria)?'<div class="text-center"><span class="icon icon-envelope"></span></div>':'';
							},
							'type' => 'html'
						),
						'code',
						'subject',
						array(
							'name' => 'department_id' ,
							'value' => '$data->department->title'
						),
						array(
								'name' => 'status' ,
								'value' => '$data->statusLabels[$data->status]'
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{view} {delete}'
						),
					),
			)); ?>
		</div>
	</div>
</div>
