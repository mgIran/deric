<?php
/* @var $this AppsController */
/* @var $model Apps */
?>

<h3 class="page-name">افزودن برنامه جدید</h3>

<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash('failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('failed');?>
    </div>
<?php endif;?>

<ul class="nav nav-tabs">
    <li class="active">
        <a data-toggle="tab" href="#platform">پلتفرم</a>
    </li>
    <li class="disabled">
        <a href="#">بسته</a>
    </li>
    <li class="disabled" >
        <a href="#">اطلاعات برنامه</a>
    </li>
    <li class="disabled">
        <a href="#">تصاویر برنامه</a>
    </li>
</ul>

<div class="tab-content">
    <div id="platform" class="tab-pane fade in active">
        <?php $this->renderPartial('_platform', array('model'=>$model)); ?>
    </div>
</div>