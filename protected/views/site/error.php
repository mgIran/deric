<?php
/* @var $this SiteController */
/* @var $error array */
?>

<!-----start-wrap--------->
<div class="wrap">
    <!-----start-content--------->
    <div class="content">
        <!-----start-logo--------->
        <div class="logo">
            <h1><?php echo $code; ?></h1>
            <span><img src="<?php echo Yii::app()->theme->baseUrl.'/images/signal.png';?>"/><?php echo CHtml::encode($message);?></span>
        </div>
        <!-----end-logo--------->
    </div>
</div>
<!---------end-wrap---------->