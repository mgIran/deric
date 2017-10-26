<?php
/* @var $this AppsController */
/* @var $model Apps */
/* @var $dataProvider CActiveDataProvider */
/* @var $for string */
Yii::app()->clientScript->registerCss('inline',"
.dropzone.single{width:100%;}
");
?>

<div class="container-fluid packages-list-container">
    <a class="btn btn-success" href="#package-modal" data-toggle="modal"><i class="icon icon-plus"></i> ثبت بسته</a>
    <div class="table text-center">
        <div class="thead">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8">نام بسته</div>
            <div class="col-lg-1 col-md-1 col-sm-4 hidden-xs">نسخه</div>
            <div class="col-lg-2 col-md-2 hidden-sm hidden-xs">حجم</div>
            <div class="col-lg-2 col-md-3 hidden-sm hidden-xs">تاریخ بارگذاری</div>
            <div class="col-lg-2 col-md-3 hidden-sm hidden-xs">تاریخ انتشار</div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">وضعیت</div>
        </div>
        <div class="tbody">
            <?php $this->widget('zii.widgets.CListView', array(
                'id'=>'packages-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_package_list',
                'template'=>'{items}'
            ));?>
        </div>
    </div>

    <?php echo CHtml::beginForm();?>
        <?php echo CHtml::submitButton('ادامه', array('class'=>'btn btn-success', 'name'=>'packages-submit'));?>
    <?php echo CHtml::endForm();?>

    <div id="package-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ثبت بسته جدید</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <?php if($model->platform_id==1):?>
                                <?php echo CHtml::beginForm('','post',array('id'=>'package-info-form'));?>
                                    <label style="margin: 15px 0;">فایل بسته</label>
                                    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                        'id' => 'uploaderFile',
                                        'model' => $model,
                                        'name' => 'file_name',
                                        'maxFileSize' => 1024,
                                        'maxFiles'=>false,
                                        'url' => Yii::app()->createUrl('/developers/apps/uploadFile'),
                                        'deleteUrl' => Yii::app()->createUrl('/developers/apps/deleteUploadFile'),
                                        'acceptedFiles' => $this->formats,
                                        'serverFiles' => array(),
                                        'onSuccess' => '
                                            var responseObj = JSON.parse(res);
                                            if(responseObj.status)
                                                {serverName} = responseObj.fileName;
                                            else
                                                $(".uploader-message").text(responseObj.message).addClass("error");
                                        ',
                                    ));?>
                                    <label style="margin: 15px 0;">تغییرات نسخه</label>
                                    <?php $this->widget('ext.ckeditor.CKEditor',array(
                                        'model' => $model,
                                        'attribute' => 'change_log',
                                        'config' =>'basic'
                                    )); ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php echo CHtml::hiddenField('for', $for);?>
                                            <?php echo CHtml::hiddenField('app_id', $model->id);?>
                                            <?php echo CHtml::hiddenField('filesFolder', $model->platform->name);?>
                                            <?php echo CHtml::hiddenField('platform', $model->platform->name);?>
                                            <?php echo CHtml::button('ثبت', array('class'=>'btn btn-success pull-left', 'id'=>'submit-form', 'style'=>'margin-top:15px;'))?>
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
                                                                $('.uploader-message').text('لطفا بسته جدید را آپلود کنید.').addClass('error');
                                                                return false;
                                                            }else
                                                                $('.uploader-message').text('در حال ثبت اطلاعات بسته...').removeClass('error');
                                                        },
                                                        'success':function(data){
                                                            if(data.status){
                                                                $.fn.yiiListView.update('packages-list',{});
                                                                $('.uploader-message').text('');
                                                                $('#package-modal').modal('hide');
                                                                $('.dz-preview').remove();
                                                                $('.dropzone').removeClass('dz-started');
                                                            }
                                                            else
                                                                $('.uploader-message').text(data.message).addClass('error');
                                                        },
                                                        'error':function(){ $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error'); },
                                                        'url':'".$this->createUrl('/developers/apps/savePackage')."',
                                                        'cache':false
                                                    });
                                                    return false;
                                                });
                                            ");?>
<!--                                            --><?php //echo CHtml::ajaxSubmitButton('ثبت', $this->createUrl('/developers/apps/savePackage'), array(
//                                                'type'=>'POST',
//                                                'dataType'=>'JSON',
//                                                'data'=>'js:$("#package-info-form").serialize()',
//                                                'beforeSend'=>"js:function(){
//                                                    if($('input[type=\"hidden\"][name=\"Apps[file_name]\"]').length==0){
//                                                        $('.uploader-message').text('لطفا بسته جدید را آپلود کنید.').addClass('error');
//                                                        return false;
//                                                    }else
//                                                        $('.uploader-message').text('در حال ثبت اطلاعات بسته...').removeClass('error');
//                                                }",
//                                                'success'=>"js:function(data){
//                                                    if(data.status){
//                                                        $.fn.yiiListView.update('packages-list',{});
//                                                        $('.uploader-message').text('');
//                                                        $('#package-modal').modal('hide');
//                                                        $('.dz-preview').remove();
//                                                        $('.dropzone').removeClass('dz-started');
//                                                    }
//                                                    else
//                                                        $('.uploader-message').text(data.message).addClass('error');
//                                                }",
//                                                'error'=>"js:function(){ $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error'); }",
//                                            ), array('class'=>'btn btn-success pull-left'));?>
                                            <h5 class="uploader-message error pull-right"></h5>
                                        </div>
                                    </div>
                                <?php echo CHtml::endForm();?>
                            <?php else:?>
                                <?php echo CHtml::beginForm('','post',array('id'=>'package-info-form'));?>
                                    <label style="margin: 15px 0;">فایل بسته</label>
                                    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                        'id' => 'uploaderFile',
                                        'model' => $model,
                                        'name' => 'file_name',
                                        'maxFileSize' => 1024,
                                        'maxFiles' => false,
                                        'url' => Yii::app()->createUrl('/developers/apps/uploadFile'),
                                        'deleteUrl' => Yii::app()->createUrl('/developers/apps/deleteUploadFile'),
                                        'acceptedFiles' => $this->formats,
                                        'serverFiles' => array(),
                                        'onSuccess' => '
                                            var responseObj = JSON.parse(res);
                                            if(responseObj.status)
                                                {serverName} = responseObj.fileName;
                                            else
                                                $(".uploader-message").text(responseObj.message).addClass("error");
                                        ',
                                    ));?>
                                    <label style="margin: 15px 0;">تغییرات نسخه</label>
                                    <?php $this->widget('ext.ckeditor.CKEditor',array(
                                        'model' => $model,
                                        'attribute' => 'change_log',
                                        'config' =>'basic'
                                    )); ?>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <?php echo CHtml::textField('version', '', array('class'=>'form-control', 'placeholder'=>'ورژن *'));?>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <?php echo CHtml::textField('package_name', '', array('class'=>'form-control', 'placeholder'=>'نام بسته *'));?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php echo CHtml::hiddenField('for', $for);?>
                                            <?php echo CHtml::hiddenField('app_id', $model->id);?>
                                            <?php echo CHtml::hiddenField('filesFolder', $model->platform->name);?>
                                            <?php echo CHtml::hiddenField('platform', $model->platform->name);?>
                                            <?php echo CHtml::button('ثبت', array('class'=>'btn btn-success pull-left', 'id'=>'submit-form', 'style'=>'margin-top:15px;'))?>
                                            <?php Yii::app()->clientScript->registerScript('ajax-submit',"
                                                jQuery('body').on('click','#submit-form',function(){
                                                    for ( instance in CKEDITOR.instances )
                                                            CKEDITOR.instances[instance].updateElement();
                                                    jQuery.ajax({
                                                        'type':'POST',
                                                        'dataType':'JSON',
                                                        'data':$(\"#package-info-form\").serialize(),
                                                        'beforeSend':function(){
                                                            if($('#package-info-form #version').val()=='' || $('#package-info-form #package_name').val()==''){
                                                                $('.uploader-message').text('لطفا فیلد های ستاره دار را پر کنید.').addClass('error');
                                                                return false;
                                                            }else if($('input[type=\"hidden\"][name=\"Apps[file_name]\"]').length==0){
                                                                $('.uploader-message').text('لطفا بسته جدید را آپلود کنید.').addClass('error');
                                                                return false;
                                                            }else
                                                                $('.uploader-message').text('در حال ثبت اطلاعات بسته...').removeClass('error');
                                                        },
                                                        'success':function(data){
                                                            if(data.status){
                                                                $.fn.yiiListView.update('packages-list',{});
                                                                $('.uploader-message').text('');
                                                                $('#package-modal').modal('hide');
                                                            }
                                                            else
                                                                $('.uploader-message').text(data.message).addClass('error');
                                                            $('.dz-preview').remove();
                                                            $('.dropzone').removeClass('dz-started');
                                                            $('#package-info-form #version').val('');
                                                            $('#package-info-form #package_name').val('');
                                                        },
                                                        'error':function(){ $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error'); },
                                                        'url':'".$this->createUrl('/developers/apps/savePackage')."',
                                                        'cache':false
                                                    });
                                                    return false;
                                                });
                                            ");?>
<!--                                            --><?php //echo CHtml::ajaxSubmitButton('ثبت', $this->createUrl('/developers/apps/savePackage'), array(
//                                                'type'=>'POST',
//                                                'dataType'=>'JSON',
//                                                'data'=>'js:$("#package-info-form").serialize()',
//                                                'beforeSend'=>"js:function(){
//                                                    if($('#package-info-form #version').val()=='' || $('#package-info-form #package_name').val()==''){
//                                                        $('.uploader-message').text('لطفا فیلد های ستاره دار را پر کنید.').addClass('error');
//                                                        return false;
//                                                    }else if($('input[type=\"hidden\"][name=\"Apps[file_name]\"]').length==0){
//                                                        $('.uploader-message').text('لطفا بسته جدید را آپلود کنید.').addClass('error');
//                                                        return false;
//                                                    }else
//                                                        $('.uploader-message').text('در حال ثبت اطلاعات بسته...').removeClass('error');
//                                                }",
//                                                'success'=>"js:function(data){
//                                                    if(data.status){
//                                                        $.fn.yiiListView.update('packages-list',{});
//                                                        $('.uploader-message').text('');
//                                                        $('#package-modal').modal('hide');
//                                                    }
//                                                    else
//                                                        $('.uploader-message').text(data.message).addClass('error');
//                                                    $('.dz-preview').remove();
//                                                    $('.dropzone').removeClass('dz-started');
//                                                    $('#package-info-form #version').val('');
//                                                    $('#package-info-form #package_name').val('');
//                                                }",
//                                                'error'=>"js:function(){ $('.uploader-message').text('فایل ارسالی ناقص می باشد.').addClass('error'); }",
//                                            ), array('class'=>'btn btn-success pull-left'));?>
                                            <h5 class="uploader-message error pull-right"></h5>
                                        </div>
                                    </div>
                                <?php echo CHtml::endForm();?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Yii::app()->clientScript->registerCss('package-form','
#package-info-form input[type="text"]{margin-top:20px;}
#package-info-form input[type="submit"], .uploader-message{margin-top:20px;}
.uploader-message{line-height:32px;}
');?>