<div class="login-form">

<!--    --><?php //echo CHtml::beginForm(Yii::app()->createUrl('/users/public/changePassword'), 'post', array(
//        'id'=>'change-password-form',
//    ));?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'beforeValidate' => "js:function(form) {
                $('.loading-container').fadeIn();
                return true;
            }",
            'afterValidate' => "js:function(form) {
                $('.loading-container').stop().hide();
                return true;
            }",
        ),
    )); ?>

    <?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-success fade in">
            <?php echo Yii::app()->user->getFlash('success');?>
        </div>
    <?php elseif(Yii::app()->user->hasFlash('failed')):?>
        <div class="alert alert-danger fade in">
            <?php echo Yii::app()->user->getFlash('failed');?>
        </div>
    <?php endif;?>

    <h1>تغییر کلمه عبور</h1>

    <div class="row">
        <?php echo $form->passwordField($model,'password',array('class'=>'transition','placeholder'=>'کلمه عبور')); ?>
        <?php echo $form->error($model,'password'); ?>
        <span class="transition icon-key"></span>
    </div>
    <div class="row">
        <?php echo $form->passwordField($model,'repeatPassword',array('class'=>'transition','placeholder'=>'تکرار کلمه عبور')); ?>
        <?php echo $form->error($model,'repeatPassword'); ?>
        <span class="transition icon-key"></span>
    </div>
    <div class="row">
        <?php echo CHtml::SubmitButton('ارسال', array('class'=>'transition'));?>
    </div>
    <?php $this->endWidget(); ?>

    <p><a href="<?php echo $this->createUrl('/login');?>">ورود به حساب کاربری</a></p>

    <div class="loading-container">
        <div class="overly"></div>
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
</div>