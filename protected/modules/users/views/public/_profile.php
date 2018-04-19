<?php
/* @var $this PublicController */
/* @var $model Users */
/* @var $avatar array */
?>
<?php $this->renderPartial('//layouts/_flashMessage');?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'users-form',
    'htmlOptions' => array('class' => 'inline-form'),
    'enableAjaxValidation'=>true,
    'action' => $this->createUrl('profile')
)); ?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <div class="form-group">
            <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                'id' => 'uploaderLogo',
                'model' => $model,
                'name' => 'avatar',
                'containerClass' => '',
                'dictDefaultMessage'=>$model->userDetails->getAttributeLabel('avatar').' را به اینجا بکشید',
                'maxFiles' => 1,
                'maxFileSize' => 0.5, //MB
                'data'=>array('user_id'=>$model->id),
                'url' => $this->createUrl('upload'),
                'deleteUrl' => $this->createUrl('deleteUpload'),
                'acceptedFiles' => '.jpg, .jpeg, .png',
                'serverFiles' => $model->userDetails->avatar?new UploadedFiles('uploads/users/avatar', [$model->userDetails->avatar]):[],
                'onSending' => '
                    $(".userinfo").addClass("uploading");
                ',
                'onSuccess' => '
                    var responseObj = JSON.parse(res);
                    if(responseObj.status){
                        {serverName} = responseObj.fileName;
                        $(".uploader-message").html("");
                    }
                    else{
                    alert(responseObj);
                        $(".uploader-message").html(responseObj.message);
                        this.removeFile(file);
                    }
                ',
            )); ?>
            <div class="uploader-message error"></div>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'fa_name',array('placeholder'=>$model->userDetails->getAttributeLabel('fa_name').' *','class'=>'form-control','maxlength'=>50)); ?>
            <?php echo $form->error($model->userDetails,'fa_name'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'en_name',array('placeholder'=>$model->userDetails->getAttributeLabel('en_name').' *','class'=>'form-control','maxlength'=>50)); ?>
            <?php echo $form->error($model->userDetails,'en_name'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'fa_web_url',array('placeholder'=>$model->userDetails->getAttributeLabel('fa_web_url'),'class'=>'form-control','maxlength'=>255)); ?>
            <?php echo $form->error($model->userDetails,'fa_web_url'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'en_web_url',array('placeholder'=>$model->userDetails->getAttributeLabel('en_web_url'),'class'=>'form-control','maxlength'=>255)); ?>
            <?php echo $form->error($model->userDetails,'en_web_url'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'national_code',array('placeholder'=>$model->userDetails->getAttributeLabel('national_code').' *','class'=>'form-control','maxlength'=>10)); ?>
            <?php echo $form->error($model->userDetails,'national_code'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'phone',array('placeholder'=>$model->userDetails->getAttributeLabel('phone').' *','class'=>'form-control','maxlength'=>8)); ?>
            <?php echo $form->error($model->userDetails,'phone'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'zip_code',array('placeholder'=>$model->userDetails->getAttributeLabel('zip_code').' *','class'=>'form-control','maxlength'=>10)); ?>
            <?php echo $form->error($model->userDetails,'zip_code'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model->userDetails,'address',array('placeholder'=>$model->userDetails->getAttributeLabel('address').' *','class'=>'form-control','maxlength'=>1000)); ?>
            <?php echo $form->error($model->userDetails,'address'); ?>
        </div>

        <div class="form-group">
            <?php echo CHtml::submitButton('ذخیره',array('class'=>'btn btn-success pull-left')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>