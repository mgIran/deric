<?php
/* @var $this PanelController */
/* @var $text String */
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">قرارداد توسعه دهندگان</h3>
    </div>
    <div class="panel-body step-content">
        <div class="container-fluid">
            <div class="text"><?php echo $text;?></div>
            <a href="<?php echo Yii::app()->createUrl('/developers/panel/signup/step/profile')?>" class="btn btn-success pull-left">با شرایط و قوانین موافقم</a>
        </div>
    </div>
</div>