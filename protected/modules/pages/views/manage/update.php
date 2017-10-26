<?php
/* @var $this PagesManageController */
/* @var $model Pages */
$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'ویرایش',
);
$template = '{view} {update} {delete}';

if($this->categorySlug == 'document' || $this->categorySlug == 'free')
{
    $this->menu=array(
        array('label'=>'افزودن', 'url'=>array('manage/create/slug/'.$this->categorySlug)),
        array('label'=>'مدیریت', 'url'=>array('manage/admin/slug/'.$this->categorySlug)),
    );
}
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">ویرایش <?php echo $model->title; ?></h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>