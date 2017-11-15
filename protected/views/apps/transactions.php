<?php
/* @var $this AppsController */
/* @var $model UserTransactions */

$this->breadcrumbs=array(
	'ریز تراکنش ها',
);
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">تراکنش ها</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'transactions-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				array(
					'name' => 'user_id',
					'value' => '$data->user->userDetails->showName',
					'filter' => CHtml::listData(Users::model()->findAll('status != "deleted"'),'id', 'userDetails.showName')
				),
				array(
					'name' => 'date',
					'value' => 'JalaliDate::date("Y/m/d H:i", $data->date)',
					'filter' => false
				),
				array(
					'name' => 'status',
					'value' => function($data){
						$class = $data->status=='paid'?'success':'danger';
						return "<span class='label label-{$class}'>{$data->statusLabels[$data->status]}</span>";
					},
					'filter' => CHtml::activeDropDownList($model,'status',$model->statusLabels,array('prompt' => 'همه')),
					'type' => 'raw'
				),
				array(
					'name' => 'token',
					'value' => function($data){
						return "<b style='letter-spacing: 2px'>{$data->token}</b>";
					},
					'type' => 'raw'
				),
			),
		)); ?>
		</div>
	</div>
</div>