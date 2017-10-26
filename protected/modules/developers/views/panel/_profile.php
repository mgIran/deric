<?php
/* @var $this PanelController */
/* @var $model UserDetails */
/* @var $form CActiveForm */
/* @var $nationalCardImage array */
/* @var $registrationCertificateImage array */
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">اطلاعات قرارداد</h3>
    </div>
    <div class="panel-body step-content information-step">
        <div class="container-fluid">

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#real">حقیقی</a></li>
                <li><a data-toggle="tab" href="#legal">حقوقی</a></li>
            </ul>

            <div class="tab-content">
                <div id="real" class="tab-pane fade in active">
                    <div class="form col-md-10 col-md-offset-1">

                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'update-real-profile-form',
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // There is a call to performAjaxValidation() commented in generated controller code.
                            // See class documentation of CActiveForm for details on this.
                            'enableAjaxValidation'=>true,
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                            )
                        )); ?>

                        <?php echo $form->hiddenField($model, 'type', array('value'=>'real'));?>

                        <?php echo $form->errorSummary($model); ?>

                        <p>لطفا فرم زیر را با دقت تکمیل نمایید. موارد ستاره دار الزامی هستند.</p>

                        <div class="form-group">
                            <?php echo $form->textField($model,'fa_name',array('placeholder'=>$model->getAttributeLabel('fa_name').' *','maxlength'=>50,'class'=>'form-control')); ?>
                            <p class="desc">نام باید عبارتی با حداکثر 50 حرف باشد که از حروف و اعداد فارسی و انگلیسی، فاصله و نیم‌فاصله تشکیل شده باشد.</p>
                            <?php echo $form->error($model,'fa_name'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'fa_web_url',array('placeholder'=>$model->getAttributeLabel('fa_web_url'),'maxlength'=>255,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'fa_web_url'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'en_name',array('placeholder'=>$model->getAttributeLabel('en_name').' *','maxlength'=>50,'class'=>'form-control')); ?>
                            <p class="desc">نام باید عبارتی با حداکثر 50 حرف باشد که از حروف و اعداد فارسی و انگلیسی، فاصله و نیم‌فاصله تشکیل شده باشد.</p>
                            <?php echo $form->error($model,'en_name'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'en_web_url',array('placeholder'=>$model->getAttributeLabel('en_web_url'),'maxlength'=>255,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'en_web_url'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'nickname',array('placeholder'=>$model->getAttributeLabel('nickname').' *','maxlength'=>20,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'nickname'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'national_code',array('placeholder'=>$model->getAttributeLabel('national_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'national_code'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'phone',array('placeholder'=>$model->getAttributeLabel('phone').' *','maxlength'=>11,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'phone'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'zip_code',array('placeholder'=>$model->getAttributeLabel('zip_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'zip_code'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textArea($model,'address',array('placeholder'=>$model->getAttributeLabel('address').' *','maxlength'=>1000,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'address'); ?>
                        </div>

                        <div class="form-group">
                            <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                'id' => 'national-card-uploader',
                                'model' => $model,
                                'name' => 'national_card_image',
                                'dictDefaultMessage'=>$model->getAttributeLabel('national_card_image').' را به اینجا بکشید',
                                'maxFiles' => 1,
                                'maxFileSize' => 0.5, //MB
                                'data'=>array('user_id'=>$model->user_id),
                                'url' => $this->createUrl('/developers/panel/uploadNationalCardImage'),
                                'acceptedFiles' => 'image/jpeg , image/png',
                                'serverFiles' => $nationalCardImage,
                                'onSuccess' => '
                            var responseObj = JSON.parse(res);
                            if(responseObj.state == "ok")
                            {
                                {serverName} = responseObj.fileName;
                            }else if(responseObj.state == "error"){
                                console.log(responseObj.msg);
                            }
                        ',
                            ));?>
                        </div>

                        <div class="buttons">
                            <?php echo CHtml::submitButton('ثبت و ادامه',array('class'=>'btn btn-success pull-left')); ?>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                </div>
                <div id="legal" class="tab-pane fade">
                    <div class="form col-md-10 col-md-offset-1">

                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'update-legal-profile-form',
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // There is a call to performAjaxValidation() commented in generated controller code.
                            // See class documentation of CActiveForm for details on this.
                            'enableAjaxValidation'=>true,
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                            )
                        )); ?>

                        <?php echo $form->hiddenField($model, 'type', array('value'=>'legal'));?>

                        <?php echo $form->errorSummary($model); ?>

                        <p>لطفا فرم زیر را با دقت تکمیل نمایید. موارد ستاره دار الزامی هستند.</p>

                        <div class="form-group">
                            <?php echo $form->textField($model,'fa_name',array('placeholder'=>'نام و نام خانوادگی *','maxlength'=>50,'class'=>'form-control')); ?>
                            <p class="desc">باید عبارتی با حداکثر 50 حرف باشد که از حروف و اعداد فارسی و انگلیسی، فاصله و نیم‌فاصله تشکیل شده باشد.</p>
                            <?php echo $form->error($model,'fa_name'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'nickname',array('placeholder'=>$model->getAttributeLabel('nickname').' *','maxlength'=>50,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'nickname'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'fa_web_url',array('placeholder'=>'آدرس سایت','maxlength'=>255,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'fa_web_url'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->dropDownList($model,'post',$model->postLabels,array('class'=>'form-control', 'prompt'=>$model->getAttributeLabel('post'))); ?>
                            <?php echo $form->error($model,'post'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'company_name',array('placeholder'=>$model->getAttributeLabel('company_name').' *','maxlength'=>50,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'company_name'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'registration_number',array('placeholder'=>$model->getAttributeLabel('registration_number').' *','maxlength'=>50,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'registration_number'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'phone',array('placeholder'=>$model->getAttributeLabel('phone').' *','maxlength'=>11,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'phone'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textField($model,'zip_code',array('placeholder'=>$model->getAttributeLabel('zip_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'zip_code'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->textArea($model,'address',array('placeholder'=>$model->getAttributeLabel('address').' *','maxlength'=>1000,'class'=>'form-control')); ?>
                            <?php echo $form->error($model,'address'); ?>
                        </div>

                        <div class="form-group">
                            <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                'id' => 'registration-certificate-uploader',
                                'model' => $model,
                                'name' => 'registration_certificate_image',
                                'dictDefaultMessage'=>$model->getAttributeLabel('registration_certificate_image').' را به اینجا بکشید',
                                'maxFiles' => 1,
                                'maxFileSize' => 0.5, //MB
                                'data'=>array('user_id'=>$model->user_id),
                                'url' => $this->createUrl('/developers/panel/uploadRegistrationCertificateImage'),
                                'acceptedFiles' => 'image/jpeg , image/png',
                                'serverFiles' => $registrationCertificateImage,
                                'onSuccess' => '
                                    var responseObj = JSON.parse(res);
                                    if(responseObj.state == "ok")
                                    {
                                        {serverName} = responseObj.fileName;
                                    }else if(responseObj.state == "error"){
                                        console.log(responseObj.msg);
                                    }
                                ',
                            ));?>
                        </div>

                        <div class="buttons">
                            <?php echo CHtml::submitButton('ثبت و ادامه',array('class'=>'btn btn-success pull-left')); ?>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                </div>
            </div>

        </div>
    </div>
</div>