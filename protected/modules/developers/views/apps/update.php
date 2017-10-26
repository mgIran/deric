<?php
/* @var $this AppsController */
/* @var $model Apps */
/* @var $imageModel AppImages */
/* @var $images [] */
/* @var $step integer */
/* @var $packagesDataProvider CActiveDataProvider */
?>

<h3 class="page-name">ویرایش <?= $model->title; ?></h3>
<ul class="nav nav-tabs">
    <li>
        <a data-toggle="tab" href="#platform">پلتفرم</a>
    </li>
    <li <?= !isset($step) || $step == 1 ?'class="active"':''; ?>>
        <a data-toggle="tab" href="#packages">بسته</a>
    </li>
    <li class="<? if($step == 2)echo 'active';elseif($step<2)echo 'disabled';?>">
        <a data-toggle="<?= !isset($step)?'':'tab'?>" href="#info">اطلاعات برنامه</a>
    </li>
    <li class="<? if($step == 3)echo 'active';elseif($step<3)echo 'disabled';?>">
        <a data-toggle="<?= ($step == 3)?'tab':''?>" href="#images">تصاویر برنامه</a>
    </li>
</ul>

<div class="tab-content">
    <?php $this->renderPartial('//layouts/_flashMessage');?>
    <div id="platform" class="tab-pane fade">
        <p>پلتفرم انتخاب شده دیگر قابل ویرایش نمی باشد.</p>
        <h4>پلتفرم: <small><?php echo $model->platform->platformsLabel[$model->platform->name];?></small></h4>
    </div>
    <div id="packages" class="tab-pane fade <?= !isset($step) || $step == 1?'in active':''; ?>">
        <?php $this->renderPartial('_package', array(
            'model'=>$model,
            'dataProvider'=>$packagesDataProvider,
            'for'=>(Yii::app()->request->getParam('new')=='1')?'new_app':'old_app'
        ));?>
    </div>
    <div id="info" class="tab-pane fade <?= $step == 2?'in active':''; ?>">
        <?php if($step>=2):?>
            <?php $this->renderPartial('_form', array(
                'model'=>$model,
                'icon' => $icon,
                'tax'=>$tax,
                'commission'=>$commission,
            ));?>
        <?php endif;?>
    </div>
    <div id="images" class="tab-pane fade <?= $step == 3?'in active':''; ?>">
        <?php if($step>=3):?>
            <?php $this->renderPartial('_images_form', array(
                'model'=>$model,
                'imageModel'=>$imageModel,
                'images' => $images
            ));?>
        <?php endif;?>
    </div>
</div>