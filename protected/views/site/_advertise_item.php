<?php /* @var $data Advertises */ ?>

<div class="advertise-item">
    <a href="<?php echo $data->app->getViewUrl()?>">
        <img src="<?php echo Yii::app()->baseUrl.'/uploads/advertisesCover/'.CHtml::encode($data->cover);?>">
    </a>
</div>
