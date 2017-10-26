<?php
/* @var $this BaseManageController */
/* @var $model Apps */

$this->breadcrumbs=array(
	'مدیریت',
);
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">مدیریت برنامه ها</h3>
		<a href="<?php echo Yii::app()->createUrl('/manageApps/'.$this->controller.'/create')?>" class="btn btn-default btn-sm">افزودن برنامه</a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'apps-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				'title',
				array(
					'header' => 'توسعه دهنده',
					'value' => '$data->developer_id?$data->developer->userDetails->developer_id:$data->developer_team',
					'filter' => CHtml::activeTextField($model,'devFilter')
				),
				array(
					'name' => 'category_id',
					'value' => '$data->category->fullTitle',
					'filter' => CHtml::activeDropDownList($model,'category_id',AppCategories::model()->sortList(),array('prompt' => 'همه'))
				),
				array(
					'name' => 'status',
					'value' => '$data->statusLabels[$data->status]',
					'filter' => CHtml::activeDropDownList($model,'status',$model->statusLabels,array('prompt' => 'همه'))
				),
				array(
					'name' => 'price',
					'value' => '$data->price != 0?$data->price:"رایگان"'
				),
				array(
					'header' => 'بسته',
					'value' => 'is_null($data->lastPackage)?"ندارد":$data->lastPackage->package_name',
					'filter' => CHtml::activeTextField($model,'packageFilter'),
				),
				/*
				'file_name',
				'icon',
				'description',
				'change_log',
				'permissions',
				'size',
				'version',
				'confirm',
				*/
				array(
					'class'=>'CButtonColumn',
					'buttons' => array(
						'update' => array(
							'url' => 'Yii::app()->createUrl("/manageApps/'.$this->controller.'/update", array("id"=>$data->id))'
						),
						'delete' => array(
							'url' => 'Yii::app()->createUrl("/manageApps/'.$this->controller.'/delete", array("id"=>$data->id))'
						),
						'view' => array(
							'url'=>'Yii::app()->createUrl("/apps/".$data->id."/".urlencode($data->title))',
							'options'=>array(
								'target'=>'_blank'
							),
						)
					)
				),
			),
		)); ?>
		</div>
	</div>
</div>