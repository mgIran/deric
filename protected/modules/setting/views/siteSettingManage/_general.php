<?php
/* @var $this SiteSettingManageController */
/* @var $model SiteSetting */

$this->breadcrumbs = array(
    'پیشخوان' => array('/admins/dashboard'),
    'تنظیمات'
);

?>

<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<?php Yii::app()->clientScript->registerScript('callTagIt',"
    $('#SiteSetting_buy_credit_options').tagit();
");?>
<div class="box box-info">
    <div class="box-header with-border"><h3 class="box-title">تنظیمات</h3></div>
    <div class="box-body">
        <?php $this->renderPartial('//layouts/_flashMessage') ?>
        <?
        $form = $this->beginWidget('CActiveForm',array(
            'id'=> 'general-setting',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ));
        ?>
        <? foreach($model as $field){?>
            <?php if($field->name=='buy_credit_options'):?>
                <div class="form-group">
                    <?php echo CHtml::label($field->title,''); ?>
                    <p style="clear: both;padding-right: 15px;color: #aaa">گزینه اول به عنوان انتخاب پیش فرض در نظر گرفته میشود</p>
                    <?
                    $this->widget("ext.tagIt.tagIt",array(
                        'name' => "SiteSetting[$field->name]",
                        'data' => (!empty($field->value))?CJSON::decode($field->value):''
                    ));
                    ?>
                    <ul id="credit-options"></ul>
                    <?php echo $form->error($field,'name'); ?>
                </div>
            <?php else:?>
                <div class="form-group">
                    <?php echo CHtml::label($field->title,''); ?>
                    <?php echo CHtml::textarea("SiteSetting[$field->name]",$field->value,array('size'=>60,'class'=>'form-control')); ?>
                    <?php echo $form->error($field,'name'); ?>
                </div>
            <?php endif;?>
            <?
        }
        ?>
        <div class="form-group buttons">
            <?php echo CHtml::submitButton('ذخیره',array('class' => 'btn btn-success')); ?>
        </div>
        <?
        $this->endWidget();
        ?>
    </div>
</div>