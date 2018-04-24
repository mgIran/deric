<?php
/* @var $this BaseManageController */
/* @var $model Apps */
/* @var $dataProvider CActiveDataProvider */
Yii::app()->clientScript->registerCss('inline',"
.dropzone.single{width:100%;}
a[href='#package-modal']{margin-top:20px;}
");
?>

<?php if($model->platform_id==1):
    $cols = [
        'version',
        'package_name',
        array(
            'class'=>'CButtonColumn',
            'template' => '{delete}',
            'buttons'=>array(
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("/manageApps/'.$model->platform->name.'/deletePackage/".$data->id)',
                ),
            ),
        ),
    ];
    ?>
    <?php echo CHtml::beginForm('','post',array('id'=>'package-info-form'));?>
    <label style="margin-top: 15px;">فایل بسته</label>
    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
        'id' => 'uploaderFile',
        'model' => $model,
        'name' => 'file_name',
        'maxFileSize' => 1024,
        'maxFiles'=>false,
        'url' => Yii::app()->createUrl('/manageApps/'.$model->platform->name.'/uploadFile'),
        'deleteUrl' => Yii::app()->createUrl('/manageApps/'.$model->platform->name.'/deleteUploadFile'),
        'acceptedFiles' => $this->formats,
        'serverFiles' => array(),
        'onSuccess' => '
            var responseObj = JSON.parse(res);
            if(responseObj.status)
                {serverName} = responseObj.fileName;
            else
                $(".uploader-message").text(responseObj.message);
        ',
    ));?>
    <label style="margin-top: 15px;">تغییرات نسخه</label>
    <?php $this->widget('ext.ckeditor.CKEditor',array(
        'model' => $model,
        'attribute' => 'change_log'
    )); ?>

    <label style="margin-top: 15px;">تغییرات نسخه</label>
    <?php
    $this->widget('ext.fileManager.fileManager', array(
        'id' => 'data-file-name',
        'name' => 'data_file_name',
        'url' => $this->createUrl('fetch'),
        'maxFiles' => 1,
        'serverDir' => 'uploads/apps/dataFiles'
    ));
    ?>
    <br>
    <div class="row">
        <div class="col-md-12">
            <?php echo CHtml::hiddenField('app_id', $model->id);?>
            <?php echo CHtml::hiddenField('filesFolder', $model->platform->name);?>
            <?php echo CHtml::hiddenField('platform', $model->platform->name);?>
            <?php echo CHtml::button('ثبت', array('class'=>'btn btn-success', 'id'=>'submit-form'))?>
            <?php Yii::app()->clientScript->registerScript('ajax-submit',"
                jQuery('body').on('click','#submit-form',function(){
                    for ( instance in CKEDITOR.instances )
                            CKEDITOR.instances[instance].updateElement();
                    jQuery.ajax({
                        'type':'POST',
                        'dataType':'JSON',
                        'data':$(\"#package-info-form\").serialize(),
                        'beforeSend':function(){
                            if($('input[type=\"hidden\"][name=\"Apps[file_name]\"]').length==0){
                                $('.uploader-message').text('لطفا بسته جدید را آپلود کنید.');
                                return false;
                            }else
                                $('.uploader-message').text('');
                        },
                        'success':function(data){
                            if(data.status){
                                $.fn.yiiGridView.update('packages-grid');
                                $('.uploader-message').text('');
                                $('.dz-preview').remove();
                                $('.dropzone').removeClass('dz-started');
                            }
                            else
                                $('.uploader-message').text(data.message);
                        },
                        'error':function(){
                            $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error');
                        },
                        'url':'".$this->createUrl('/manageApps/'.$model->platform->name.'/savePackage')."',
                        'cache':false
                    });
                    return false;
                });
            ");?>
        </div>
    </div>
    <?php echo CHtml::endForm();?>
<?php else:
    $cols = [
        'version',
        [
            'name' => 'download_file_url',
            'value' => function($data){
                return CHtml::link(urldecode($data->download_file_url), $data->download_file_url,['target' => '_blank']);
            },
            'htmlOptions' => [
                'class' => 'ltr text-right'
            ],
            'type' => 'raw'
        ],
        [
            'name' => 'download_file_size',
            'htmlOptions' => [
                'class' => 'ltr text-right'
            ],
        ],
        array(
            'class'=>'CButtonColumn',
            'template' => '{delete}',
            'buttons'=>array(
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("/manageApps/'.$model->platform->name.'/deletePackage/".$data->id)',
                ),
            ),
        ),
    ];
    ?>
    <?php echo CHtml::beginForm('','post',array('id'=>'package-info-form'));?>
    <div class="form-group row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?php echo CHtml::textField('download_file_url', '', array('class'=>'form-control ltr text-right', 'placeholder'=>'لینک دانلود فایل *'));?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php echo CHtml::textField('download_file_size', '', array('class'=>'form-control ltr text-right', 'placeholder'=>'حجم فایل *'));?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php echo CHtml::textField('version', '', array('class'=>'form-control', 'placeholder'=>'ورژن *'));?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label style="margin-top: 15px;">تغییرات نسخه</label>
            <?php $this->widget('ext.ckeditor.CKEditor',array(
                'model' => $model,
                'attribute' => 'change_log'
            )); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <?php echo CHtml::hiddenField('app_id', $model->id);?>
            <?php echo CHtml::hiddenField('filesFolder', $model->platform->name);?>
            <?php echo CHtml::hiddenField('platform', $model->platform->name);?>
            <?php echo CHtml::button('ثبت', array('class'=>'btn btn-success', 'id'=>'submit-form'))?>
            <?php Yii::app()->clientScript->registerScript('ajax-submit',"
                jQuery('body').on('click','#submit-form',function(){
                    for ( instance in CKEDITOR.instances )
                            CKEDITOR.instances[instance].updateElement();
                    jQuery.ajax({
                        'type':'POST',
                        'dataType':'JSON',
                        'data':$(\"#package-info-form\").serialize(),
                        'beforeSend':function(){
                            if($('#package-info-form #download_file_url').val()=='' ||
                                $('#package-info-form #download_file_size').val()=='' || 
                                $('#package-info-form #version').val()==''){
                                $('.uploader-message').text('لطفا فیلد های ستاره دار را پر کنید.');
                                return false;
                            }else
                                $('.uploader-message').text('');
                        },
                        'success':function(data){
                            if(data.status){
                                $.fn.yiiGridView.update('packages-grid');
                                $('.uploader-message').text('');
                                $('.dz-preview').remove();
                                $('.dropzone').removeClass('dz-started');
                                $('#package-info-form #download_file_url').val('');
                                $('#package-info-form #download_file_size').val('');
                                $('#package-info-form #version').val('');
                                $('#package-info-form #Apps_change_log').val('');
                            }
                            else
                                $('.uploader-message').html(data.message);
                        },
                        'error':function(){ $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error'); },
                        'url':'".$this->createUrl('/manageApps/'.$model->platform->name.'/savePackage')."',
                        'cache':false
                    });
                    return false;
                });
            ");?>
        </div>
    </div>
    <?php echo CHtml::endForm();?>
<?php endif;?>
<h5 class="uploader-message error"></h5>
<div class="table-responsive">
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'packages-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>$cols
));?>
</div>
<?php echo CHtml::button('ثبت و ادامه', array('class'=>'btn btn-success', 'onclick'=>'$(".nav a[href=\'#pics\']").trigger("click");'));?>

<?php Yii::app()->clientScript->registerCss('package-form','
#package-info-form input[type="text"]{margin-top:20px;}
#package-info-form input[type="submit"]{margin-top:20px;}
');?>