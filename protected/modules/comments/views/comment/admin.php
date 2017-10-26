<?php
$this->breadcrumbs=array(
	Yii::t('commentsModule.msg', 'Messages')=>array('index'),
	Yii::t('commentsModule.msg', 'Manage'),
);
?>

<h1><?php echo Yii::t('commentsModule.msg', 'Manage Messages');?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'comment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('commentsModule.msg', 'Model'),
			'name'=>'owner_name',
			'htmlOptions'=>array('width'=>50),
		),
		array(
			'header'=>Yii::t('commentsModule.msg', 'Model\'s ID'),
			'name'=>'owner_id',
			'htmlOptions'=>array('width'=>50),
		),
		array(
			'header'=>Yii::t('commentsModule.msg', 'User Name'),
			'value'=>'$data->userName',
			'htmlOptions'=>array('width'=>80),
		),
		array(
			'header'=>Yii::t('commentsModule.msg', 'Link'),
			'value'=>'CHtml::link(CHtml::link(Yii::t("CommentsModule.msg", "Link"), $data->pageUrl, array("target"=>"_blank")))',
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
			'filter'=>Comment::model()->getStatuses(),
		),
		array(
			'class'=>'CButtonColumn',
			'deleteButtonImageUrl'=>false,
			'buttons'=>array(
				'approve' => array(
					'label'=>Yii::t('commentsModule.msg', 'Approve'),
					'url'=>'Yii::app()->urlManager->createUrl(CommentsModule::APPROVE_ACTION_ROUTE, array("id"=>$data->comment_id))',
					'options'=>array('style'=>'margin-right: 5px;'),
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
			),
			'template'=>'{approve} {delete}',
		),
	),
)); ?>
