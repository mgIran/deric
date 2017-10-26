<?php
/* @var $this AdminsManageController */
/* @var $model Admins */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScript('resetForm','document.getElementById("admins-form").reset();');
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admins-form',
	'enableAjaxValidation'=>true,

)); ?>
    <div class="message"></div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'username' ,array('class'=>'col-lg-2 control-label')); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>100 , (!$model->isNewRecord?'disabled':'s') => true)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
    <?php
    if(!$model->isNewRecord){
    ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'oldPassword',array('class'=>'col-lg-2 control-label')); ?>
            <?php echo $form->passwordField($model,'oldPassword',array('size'=>50,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'oldPassword'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'newPassword',array('class'=>'col-lg-2 control-label')); ?>
            <?php echo $form->passwordField($model,'newPassword',array('size'=>50,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>
    <?php
    }else{
    ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'password',array('class'=>'col-lg-2 control-label')); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

    <?php } ?>

    <div class="form-group">
        <?php echo $form->labelEx($model,'repeatPassword',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->passwordField($model,'repeatPassword',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'repeatPassword'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'email',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->emailField($model,'email',array('size'=>50,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'role_id',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->dropDownList($model,'role_id' ,CHtml::listData(  AdminRoles::model()->findAll() , 'id' , 'name')); ?>
        <?php echo $form->error($model,'role_id'); ?>
    </div>

	<div class="form-group buttons">
		<?php echo CHtml::ajaxButton($model->isNewRecord ? 'افزودن' : 'ویرایش',
            $model->isNewRecord ? Yii::app()->createUrl("/admins/manage/create"):Yii::app()->createUrl("/admins/manage/update/id/$model->id"),
            array(
                'type' => 'POST',
                'data' => 'js: $("#admins-form").serialize()',
                'dataType' => 'json',
                'success' => 'function(data){
                    $("html ,body").animate({
                        scrollTop: $("body").offset().top
                    },"fast");
                    if(data.result == "success"){
                        $(".message").html("<div class=\'alert alert-block alert-success fade in\'><button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>"+data.msg+"</div>");
                    }
                    else if(data.result == "failed")
                        $(".message").html("<div class=\'alert alert-block alert-danger fade in\'><button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>"+data.msg+"</div>");
                }',
            ),
            array('class'=>'btn btn-success')
        ); ?>
	</div>

<?php $this->endWidget(); ?>