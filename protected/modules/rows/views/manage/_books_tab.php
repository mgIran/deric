<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */
/* @var $form CActiveForm */


Yii::app()->clientScript->registerScript('add-remove-books', "
$('body').on('click','.add-in-row',function(){
    var rowId = $(this).parents('tr').attr('data-row-id');
    var categoryID = $(this).parents('tr').attr('data-category-id');
	$.ajax({
		url: '".$this->createUrl('add')."',
		type:'POST',
		data:{row_id:rowId, app_category_id:categoryID},
		beforeSend:function(){
    		$('#books-tab .loading-container').show();
		},
		success:function(res){
    		$('#books-tab .loading-container').hide();
    		$('#row-category-grid').yiiGridView('update');
	        $('#other-category-grid').yiiGridView('update');
		},
	});
	return false;
});
$('body').on('click','.remove-from-row',function(){
	var rowId = $(this).parents('tr').attr('data-row-id');
    var categoryID = $(this).parents('tr').attr('data-category-id');
	$.ajax({
		url: '".$this->createUrl('remove')."',
		type:'POST',
		data:{row_id:rowId, app_category_id:categoryID},
		beforeSend:function(){
    		$('#books-tab .loading-container').show();
		},
		success:function(res){
    		$('#books-tab .loading-container').hide();
    		$('#row-category-grid').yiiGridView('update');
	        $('#other-category-grid').yiiGridView('update');
		},
	});
});
");

?>

<div class="form relative">
	<? $this->renderPartial('//layouts/_flashMessage'); ?>
	<? $this->renderPartial('//layouts/_loading'); ?>
	<div>
        <h4>لیست دسته بندی های این ردیف</h4>
		<div class="description">** می توانید با جابجا کردن سطرها دسته بندی های ردیف را مرتب سازی کنید.</div>
		<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
			'orderField' => 'order',
			'idField' => 'row_id,app_category_id',
			'orderUrl' => 'order',
			'id'=>'row-category-grid',
			'beforeAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').show(); }',
    		'afterAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').hide(); }',
			'dataProvider'=>$model->searchAppCategories(),
            'rowHtmlOptionsExpression'=>'array("data-row-id"=>$data->row_id,"data-category-id"=>$data->app_category_id)',
			'columns'=>array(
				'category.title',
                [
                    'header' => 'والد',
                    'name' => 'category.parent.title'
                ],
				array(
					'class'=>'CButtonColumn',
					'template' => '{remove}',
					'buttons' => array(
						'remove' => array(
							'label'=>'حذف',
							'url'=>'"#"',
							'options'=>array('class' => 'remove-from-row btn btn-danger')
						)
					)
				),
			),
		)); ?>
	</div>
	<div>
		<div class="description">لیست دسته بندی های دیگر</div>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'other-category-grid',
			'beforeAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').show(); }',
			'afterAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').hide(); }',
			'dataProvider'=>$model->searchOtherAppCategories(),
            'rowHtmlOptionsExpression'=>'array("data-category-id"=>$data->id,"data-row-id"=>'.$model->id.')',
			'columns'=>array(
				'title',
				[
                    'header' => 'والد',
                    'name' => 'parent.title'
                ],
				array(
					'class'=>'CButtonColumn',
					'template' => '{add}',
					'buttons' => array(
						'add' => array(
							'label'=>'انتخاب',
							'url'=>'"#"',
							'options'=>array('class' => 'add-in-row btn btn-success')
						)
					)
				),
			),
		)); ?>
	</div>

</div><!-- form -->