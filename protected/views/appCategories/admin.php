<?php
/* @var $this AppCategoriesController */
/* @var $model AppCategories */

$this->breadcrumbs=array(
	'دسته بندی های برنامه',
	'مدیریت',
);
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">مدیریت دسته بندی ها</h3>
		<a href="<?php echo $this->createUrl('create')?>" class="btn btn-default">افزودن دسته بندی</a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'app-categories-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'title',
					array(
						'header' => 'والد',
						'name' => 'parent.title',
						'filter' => CHtml::activeDropDownList($model,'parentFilter',CHtml::listData(AppCategories::model()->findAll('parent_id IS NULL'),'title','title'))
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
