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
    <label for="uploader">تصاویر</label>
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
    <h5>ویدئوها</h5>
    <a href="#" data-toggle="modal" data-target="#iframe-modal" class="btn btn-info">افزودن ویدئو</a>
    <div class="table-responsive">
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'iframes-grid',
            'dataProvider'=>new CArrayDataProvider($model->iframes),
            'itemsCssClass' => 'table table-hover',
            'columns'=>array(
                [
                    'header' => 'کد ویدئو',
                    'name' => 'iframe',
                    'type' => 'raw'
                ],
                array(
                    'class'=>'CButtonColumn',
                    'template' => '{delete}',
                    'buttons' => array(
                        'delete' => array(
                            'url' => 'Yii::app()->createUrl("/manageApps/imagesManage/delete", array("id"=>$data->id))'
                        )
                    )
                ),
            ),
        )); ?>
    </div>
</div>
<div class="form-group">
    <div class="input-group buttons">
        <?php echo CHtml::submitButton('تایید نهایی',array('class'=>'btn btn-success')); ?>
    </div>
</div>
<?
$this->endWidget();
?>


<div class="modal fade" id="iframe-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>افزودن کد ویدئو
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </h4>
            </div>
            <div class="modal-body">
                <?php $this->renderPartial('//layouts/_loading') ?>
                <?php
                $iframe = new AppImages('insert_iframe');
                $iframe->type = AppImages::TYPE_IFRME;
                ?>
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'apps-iframe-form',
                    'action' => $this->createUrl('/manageApps/imagesManage/createIframe?ajax=apps-iframe-form'),
                    'enableAjaxValidation'=>false,
                    'enableClientValidation'=>true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'afterValidate' => 'js:function(form ,data ,hasError){
                            if(!hasError)
                            {
                                var loading = $("#iframe-modal .loading-container");
                                var url = form.attr("action");
                                submitAjaxForm(form ,url ,loading ,"if(html.state == \'ok\'){ $.fn.yiiGridView.update(\'iframes-grid\'); $(\'#iframe-modal\').modal(\'hide\'); $(\'#apps-iframe-form #AppImages_iframe\').val(\'\');  }");
                            }
                        }'
                    )
                ));
                echo CHtml::hiddenField('AppImages[app_id]',$model->id);
                ?>

                <div class="form-group">
                    <?= $form->labelEx($iframe, 'iframe') ?>
                    <?= $form->textArea($iframe, 'iframe', array(
                        'size' => 60, 'rows'=>5,
                        'class' => 'form-control ltr',
                        'style' => 'max-width: 100% !important;min-width: 100% !important;min-height: 100px !important;'
                    )); ?>
                    <?= $form->error($iframe, 'iframe') ?>
                </div>

                <div class="form-group buttons">
                    <?php echo CHtml::submitButton('ثبت',array('class'=>'btn btn-success')); ?>
                </div>
                <? $this->endWidget();?>
            </div>
        </div>
    </div>
</div>

