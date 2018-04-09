<?php
/* @var $this ManageController */
/* @var $model AppAdvertises */
/* @var $form CActiveForm */
/* @var $cover [] */

$apps = array();
if($model->isNewRecord) {
    // get valid apps for advertising
    $criteria = Apps::model()->getValidApps($model->platform_id);
    $criteria->together = true;
    $criteria->with[] = 'advertise';
    $criteria->addCondition('advertise.id IS NULL');
    $apps = Apps::model()->findAll($criteria);
}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'special-advertises-form',
    'enableAjaxValidation'=>false,
));?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'cover'); ?>
        <?php
        $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
            'id' => 'uploaderAd',
            'model' => $model,
            'name' => 'cover',
            'maxFiles' => 1,
            'maxFileSize' => 1, //MB
            'url' => $this->createUrl('upload'),
            'deleteUrl' => $this->createUrl('deleteUpload'),
            'acceptedFiles' => '.jpg, .jpeg, .png',
            'serverFiles' => $cover,
            'onSuccess' => '
                var responseObj = JSON.parse(res);
                if(responseObj.status){
                    {serverName} = responseObj.fileName;
                    $(".uploader-message").html("");
                }
                else{
                    $(".uploader-message").html(responseObj.message);
                    this.removeFile(file);
                }
            ',
        ));
        ?>
        <div class="uploader-message error"></div>
        <?php echo $form->error($model, 'cover'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('class'=>'form-control'));?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

<!--    <div class="form-group">-->
<!--        --><?php //echo $form->labelEx($model, 'platform_id'); ?>
<!--        --><?php //echo $form->dropDownList($model, 'platform_id', CHtml::listData(AppPlatforms::model()->findAll(), 'id', 'title'), array('class' => 'form-control')); ?>
<!--        --><?php //echo $form->error($model, 'platform_id'); ?>
<!--    </div>-->
    
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', $model->statusLabels, array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <?php
    if($model->type == AppAdvertises::SPECIAL_ADVERTISE):
    ?>
    <div class="form-group type-fields type-fields-special">
        <?php echo $form->labelEx($model, 'fade_color'); ?>
        <?php echo $form->colorField($model, 'fade_color', array('class' => 'btn', 'style'=>'width:100px;height:35px;')); ?>
        <?php echo $form->error($model, 'fade_color'); ?>
    </div>
    <?php
    endif;
    ?>

<?php
if($model->isNewRecord):
?>
    <div class="form-group">
        <?php echo CHtml::label('نوع اطلاعات',''); ?>
        <div class="clearfix"></div>
        <?php echo CHtml::radioButtonList('info', $model->app_id == null && !$model->isNewRecord?2:($model->isNewRecord?false:1) ,[1=>'برنامه داخلی', 2=>'لینک خارجی'], array('class' => 'info-trigger')); ?>
    </div>
    <?php
endif;
    ?>
    <div class="well info-fields info-fields-internal" <?= (!$model->isNewRecord && $model->app_id?'':'style="display: none;"')?>>
        <div class="form-group">
            <?php if(($apps || (!$model->isNewRecord && $model->app_id))): ?>
                <?php echo $form->labelEx($model, 'app_id'); ?>
                <? if(!$model->isNewRecord)
                    echo CHtml::textField('',$model->app->title,array('disabled'=>true, 'class' => 'form-control'));
                else
                    echo $form->dropDownList($model, 'app_id', CHtml::listData($apps, 'id', 'title'), array('class'=>'selectpicker form-control', 'data-live-search'=>'true'));
                ?>
                <?php echo $form->error($model, 'app_id'); ?>
            <?php else: ?>
                <p class="text-danger">برنامه ای جهت تبلیغ وجود ندارد.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="well info-fields info-fields-external" <?= (!$model->isNewRecord && !$model->app_id?'':'style="display: none;"')?>>
        <?php foreach ($model->externalFields as $name => $field): ?>
            <div class="form-group">
                <?php echo CHtml::label($field['label'],''); ?>
                <div class="clearfix"></div>
                <?php echo $model->renderExtraField($name) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

<?php
Yii::app()->clientScript->registerScript('type-trigger', '
    $("body").on("change", ".info-trigger", function(){
        var val = $(this).val(), css = "";
        switch(val){
            case "1": // internal
                css = "internal";
                break;
            case "2": // external
                css = "external"; 
                break;
        }
        
        $(".info-fields").not(".info-fields-"+css).hide();
        $(".info-fields-"+css).fadeIn();
    });
');
