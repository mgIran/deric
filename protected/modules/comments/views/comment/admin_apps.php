<?php
$this->breadcrumbs=array(
	Yii::t('commentsModule.msg', 'Messages')=>array('index'),
	Yii::t('commentsModule.msg', 'Manage'),
);
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo Yii::t('commentsModule.msg', 'Manage Messages');?></h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'comment-grid',
				'dataProvider'=>$model->searchApps(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'header'=>'پلتفرم',
						'value'=>'$data->apps->platform->title',
						'htmlOptions'=>array('width'=>50),
						'filter' => CHtml::activeDropDownList($model,'platformFilter',CHtml::listData(AppPlatforms::model()->findAll(),'id','title'),array('prompt'=>'همه'))
					),
					array(
						'header'=>Yii::t('commentsModule.msg', 'User Name'),
						'value'=>'$data->userName',
						'htmlOptions'=>array('width'=>80),
					),
					array(
						'header'=>'نام برنامه',
						'value'=>'CHtml::link($data->apps->title, $data->pageUrl, array("target"=>"_blank"))',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>50),
					),
					array(
						'header'=>Yii::t('commentsModule.msg', 'Comment Text'),
						'name' => 'comment_text',
					),
					array(
						'header'=>Yii::t('commentsModule.msg', 'Create Time'),
						'name'=>'create_time',
						'value'=>'JalaliDate::date("Y/m/d - H:i",$data->create_time)',
						'htmlOptions'=>array('width'=>70),
						'filter'=>false,
					),
					/*'update_time',*/
					array(
						'header'=>Yii::t('commentsModule.msg', 'Status'),
						'name'=>'status',
						'value'=>'$data->textStatus',
						'htmlOptions'=>array('width'=>50),
						'filter'=>Comment::model()->statusLabels,
					),
					array(
						'class'=>'CButtonColumn',
						'deleteButtonImageUrl'=>false,
						'buttons'=>array(
							'approve' => array(
								'label'=>Yii::t('commentsModule.msg', 'Approve'),
								'url'=>'Yii::app()->urlManager->createUrl(CommentsModule::APPROVE_ACTION_ROUTE, array("id"=>$data->comment_id))',
								'options'=>array('class' => 'btn btn-sm btn-success'),
								'visible'=>'$data->status == 0',
								'click'=>'function(){
									if(confirm("'.Yii::t('commentsModule.msg', 'Approve this comment?').'"))
									{
										$.post($(this).attr("href")).success(function(data){
											data = $.parseJSON(data);
											if(data["code"] === "success")
											{
												$.fn.yiiGridView.update("comment-grid");
											}
										});
									}
									return false;
								}',
							),
							'delete' => array(
								'options'=>array('style'=>'margin-top: 5px;','class' => 'btn btn-sm btn-danger'),
							),
						),
						'template'=>'{approve} {delete}',
					),
				),
			)); ?>
		</div>
	</div>
</div>
