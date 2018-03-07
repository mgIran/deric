<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="pull-right header">ویرایش ردیف <?php echo $model->title; ?></li>
        <li class="<?= !isset($_GET['step'])?'active':'' ?>"><a data-toggle="tab" href="#general">عمومی</a></li>
        <li class="<?= isset($_GET['step'])&&$_GET['step'] == 2?'active':'' ?>"><a data-toggle="tab" href="#books-tab" >لیست دسته بندی ها</a></li>
    </ul>
    <div class="tab-content">
        <div id="general" class="tab-pane fade <?= !isset($_GET['step'])?'in active':'' ?>">
            <?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>

        <!-- upload file -->
        <div id="books-tab" class="tab-pane fade <?= isset($_GET['step'])&&$_GET['step'] == 2?'in active':'' ?>">
            <? $this->renderPartial('//layouts/_flashMessage',array('prefix'=>'books-')); ?>
            <?php $this->renderPartial('_books_tab', array(
                'model'=>$model,
            )); ?>
        </div>
    </div>
</div>