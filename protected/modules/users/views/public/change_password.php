<?php
/* @var $this PublicController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<?php $this->renderPartial('//layouts/_flashMessage')?>
<div class="container-fluid">
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