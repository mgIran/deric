<?php
/** @var $form CActiveForm*/
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'beforeValidate' => "js:function(form) {
            $('.loading-container').fadeIn();
            return true;
        }",
        'afterValidate' => "js:function(form,data,hasError) {
            $('.loading-container').stop().hide();
            $(\"#login-btn\").val(\"در حال انتقال ...\");
            return true;
        }",
    )
)); ?>
    <div class="form-group has-feedback">
        <?php echo $form->textField($model,'username',array('class'=>'form-control ltr text-right','placeholder'=>'نام کاربری')); ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <?php echo $form->error($model,'username'); ?>
    </div>
    <div class="form-group has-feedback">
        <?php echo $form->passwordField($model,'password',array('class'=>'form-control ltr text-right','placeholder'=>'کلمه عبور')); ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <?php echo $form->error($model,'password'); ?>
    </div>
<?php if ($model->scenario == 'withCaptcha' && CCaptcha::checkRequirements()): ?>
    <div class="form-group">
        <div class="has-feedback">
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model, 'verifyCode',array('class'=>'form-control','placeholder'=>'کد امنیتی')); ?>
            <!--                <span class="fa fa-lock form-control-feedback"></span>-->
        </div>
        <?php echo $form->error($model, 'verifyCode'); ?>
    </div>
<?php endif; ?>
    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?php echo CHtml::activeName($model, 'rememberMe') ?>" value="1"> مرا بخاطر بسپار
                </label>
            </div>
        </div>
        <div class="col-xs-4">
            <button id="login-btn" type="submit" class="btn btn-primary btn-block btn-flat">ورود</button>
        </div>
    </div>
<?php $this->endWidget();
Yii::app()->clientScript->registerScript('click-on-captcha', '$("#yw0_button").click();',CClientScript::POS_READY);