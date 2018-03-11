<?php
/* @var $this Controller */
/* @var $form CActiveForm */
/* @var $model UserLoginForm */
?>
<div class="header-reg-green">
    <h5><strong>ثبت نام در <?= Yii::app()->name ?></strong></h5>
</div>
<div class="header-reg-red">
    <a class="link-reg-red" href="<?= Yii::app()->createUrl('/googleLogin') ?>"></a>
    <span class="glyphicon google"></span>
    <h5><strong>ثبت نام با اکانت گوگل</strong></h5>
</div>
<div class="or">
    <div class="or-line pull-right">
    </div>
    <div class="or-text">یا</div>
    <div class="or-line pull-left">
    </div>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'beforeValidate' => "js:function(form) {
                $('.loading-container').fadeIn();
                return true;
            }",
        'afterValidate' => "js:function(form,data,hasError) {
                $('.loading-container').stop().hide();
                $(\"#login-btn\").val(\"در حال انتقال ...\");
                return true;
            }",
        'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
                if(data.UserLoginForm_authenticate_field != undefined)
                    $("#validate-message").text(data.UserLoginForm_authenticate_field[0]).removeClass("hidden");
                else
                    $("#validate-message").addClass("hidden");
            }',
    ),
)); ?>
<div class="input-group form-item">
    <span class="glyphicon email-icon"></span>
    <?php echo $form->emailField($model,'email',array('class'=>'form-control box-item','placeholder'=>'پست الکترونیکی')); ?>
    <?php echo $form->error($model,'email'); ?>
</div>
<div class="input-group form-item">
    <span class="glyphicon password-icon"></span>
    <?php echo $form->passwordField($model,'password',array('class'=>'form-control box-item','placeholder'=>'کلمه عبور')); ?>
    <?php echo $form->error($model,'password'); ?>
</div>
<div class="row captcha">
    <?php $this->widget('CCaptcha',array(
        'captchaAction' => '/users/public/captcha',
    )); ?>
</div>
<div class="row">
    <?php echo $form->textField($model, 'verifyCode',array('class'=>"form-control",'placeholder'=>$model->getAttributeLabel('verifyCode'))); ?>
    <?php echo $form->error($model,'verifyCode'); ?>
    <span class="transition icon-lock" style="margin-top: 10px;"></span>
</div>
<div class="register-btn">
    <button type="submit" class="btn"><b>ثبت نام</b></button>
</div>
<?php $this->endWidget(); ?>
<div class="before">
    <p>قبلا ثبت نام کرده اید؟<a href="<?php echo $this->createUrl('/login');?>">ورود به حساب کاربری</a></p>
</div>

<?php $this->renderPartial('//layouts/_loading') ?>