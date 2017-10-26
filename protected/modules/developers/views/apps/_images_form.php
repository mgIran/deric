<?php
/* @var $this AppsController */
/* @var $model Apps */
/* @var $imageModel AppImages */
/* @var $images [] */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'app-images-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'action' => array('/developers/apps/images?id='.$model->id),
    'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )
));
?>
<?= $this->renderPartial('//layouts/_flashMessage' ,array('prefix' => 'images-')); ?>
<div class="form-group">
    <?php
    $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
        'id' => 'uploader',
        'model' => $imageModel,
        'name' => 'image',
        'maxFiles' => 10,
        'maxFileSize' => 1, //MB
        'data'=>array('app_id'=>$model->id),
        'url' => $this->createUrl('/developers/apps/uploadImage'),
        'deleteUrl' => $this->createUrl('/developers/apps/deleteImage'),
        'acceptedFiles' => 'image/jpeg , image/png',
        'serverFiles' => $images,
        'onSuccess' => '
                    var responseObj = JSON.parse(res);
                    if(responseObj.state == "ok")
                    {
                        {serverName} = responseObj.fileName;
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
<?
$this->endWidget();
?>