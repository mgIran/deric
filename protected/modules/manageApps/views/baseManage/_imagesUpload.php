<?php /* @var $model Apps */?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'app-images-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'action' => array('/manageApps/'.$model->platform->name.'/images?id='.$model->id),
    'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )
));
?>
<?= $this->renderPartial('//layouts/_flashMessage' ,array('prefix' => 'images-')); ?>
<div class="form-group">
    <?php if(empty($model->images)):?>
        <div class="alert alert-warning submit-image-warning">لطفا تصاویر برنامه را ثبت کنید. برنامه های بدون تصویر نمایش داده نمی شوند.</div>
    <?php endif;?>
    <?= CHtml::label('تصاویر' ,'uploaderImages' ,array('class' => 'control-label')); ?>
    <?php
    $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
        'id' => 'uploaderImages',
        'name' => 'image',
        'maxFiles' => 15,
        'maxFileSize' => 2, //MB
        'url' => $this->createUrl('/manageApps/imagesManage/upload'),
        'deleteUrl' => $this->createUrl('/manageApps/imagesManage/deleteUploaded'),
        'acceptedFiles' => 'image/jpeg , image/png',
        'serverFiles' => $images,
        'data' => array('app_id'=>$model->id),
        'onSuccess' => '
            var responseObj = JSON.parse(res);
            if(responseObj.state == "ok")
            {
                {serverName} = responseObj.fileName;
                $(".submit-image-warning").addClass("hidden");
            }else if(responseObj.state == "error"){
                console.log(responseObj.msg);
            }
        ',
    ));
    ?>
    <?php echo $form->error($model,'image'); ?>
</div>
<div class="form-group">
    <div class="input-group buttons">
        <?php echo CHtml::submitButton('تایید نهایی',array('class'=>'btn btn-success')); ?>
    </div>
</div>
<? $this->endWidget();?>