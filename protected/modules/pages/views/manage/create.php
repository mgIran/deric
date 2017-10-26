<?php
/* @var $this PagesManageController */
/* @var $model Pages */

$this->breadcrumbs=array(
    'مدیریت'=>array('admin'),
    'افزودن',
);
if($this->categorySlug == 'document')
{
    $this->breadcrumbs=array(
        'مدیریت'=>array('manage/admin/slug/document'),
        'افزودن',
    );
    $this->menu=array(
        array('label'=>'مدیریت', 'url'=>array('manage/admin/slug/document')),
    );
    $pageType = 'مستندات';
}
if($this->categorySlug == 'free')
{
    $this->menu=array(
        array('label'=>'مدیریت', 'url'=>array('admin')),
    );
    $pageType = 'صحفه';
}

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">افزودن <?= $pageType ?></h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>