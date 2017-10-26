<?php /* @var $data Advertises */ ?>

<div class="advertise-item">
    <a href="<?php echo $this->createUrl('/apps/'.CHtml::encode($data->app->id).'/'.CHtml::encode($data->app->lastPackage->package_name));?>">
        <img src="<?php echo Yii::app()->baseUrl.'/uploads/advertisesCover/'.CHtml::encode($data->cover);?>">
    </a>
</div>
