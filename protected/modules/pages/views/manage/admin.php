<?php
/* @var $this PagesManageController */
/* @var $model Pages */
$this->breadcrumbs=array(
	'مدیریت',
);
$template = '{update} {delete}';
if($this->categorySlug == 'document')
    $this->menu=array(
	    array('label'=>'افزودن مستندات جدید', 'url'=>array('manage/create/slug/document')),
    );
if($this->categorySlug == 'base')
{
    $template = '{update}';
}
if($this->categorySlug == 'free')
    $this->menu=array(
	    array('label'=>'افزودن صحفه جدید', 'url'=>array('create')),
    );

?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">مدیریت <?= $this->categoryName ?></h3>
		<?php if($this->categorySlug != 'base'):?>
			<a href="<?php echo $this->categorySlug == 'free'?$this->createUrl('create'):$this->createUrl('manage/create/slug/document') ?>" class="btn btn-sm btn-default">
				<?php echo $this->categorySlug == 'free'?'افزودن صحفه جدید':'افزودن مستندات جدید' ?>
			</a>
		<?php endif; ?>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<? $this->renderPartial('//layouts/_flashMessage'); ?>
			<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'pages-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'title',
					array(
						'name' => 'summary',
						'value' => 'mb_substr(htmlentities(strip_tags($data->summary)),0,300, "UTF-8")'
					),
					array(
						'class'=>'CButtonColumn',
						'template' => $template
					),
				),
			)); ?>
		</div>
	</div>
</div>
