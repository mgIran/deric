<?php
/* @var $this PanelController */
/* @var $model UserDetails */
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">پایان ثبت نام</h3>
    </div>
    <div class="panel-body step-content information-step">
        <div class="container-fluid">
            <div class="alert alert-info" role="alert" style="margin: 50px 0;"><h4 class="text-center" style="margin: 0">به جمع توسعه دهندگان <?= Yii::app()->name ?> خوش آمدید.</h4></div>
            <p style="margin-bottom: 30px;">از این پس می توانید برنامه های خود را در <?= Yii::app()->name ?>ارائه کنید. جهت ورود به پنل روی دکمه زیر کلیک کنید.</p>
            <form method="post" action="<?php echo $this->createUrl('/developers/panel/signup/step/finish');?>">
                <?php echo CHtml::submitButton('ورود به پنل توسعه دهندگان', array(
                    'class'=>'btn btn-danger btn-lg center-block',
                    'name'=>'goto_developer_panel'
                )); ?>
            </form>
        </div>
    </div>
</div>