<?php
/* @var $this SiteSettingManageController */
/* @var $gateway_active SiteSetting */
/* @var $gateway_variables SiteSetting */

$this->breadcrumbs = array(
    'پیشخوان' => array('/admins/dashboard'),
    'تنظیمات'
);

$variables=CJSON::decode($gateway_variables->value,false);
?>
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
        <div class="form-group">
            <?php echo CHtml::label($gateway_active->title,''); ?>
            <?php echo CHtml::dropDownList("SiteSetting[$gateway_active->name]",$gateway_active->value,[
                'zarinpal' => 'زرین پال',
                'mellat' => 'ملت'
            ],array('class' => 'form-control'))?>
            <?php echo $form->error($gateway_active,'name'); ?>
        </div>

        <!--Zarin Pal Gateway Information-->
        <div id="information-zarinpal" class="collapse-box">
            <div class="form-group">
                <?php echo CHtml::label('کد درگاه زرین پال',''); ?>
                <?php echo CHtml::textField("SiteSetting[$gateway_variables->name][merchant_id]",$variables && $variables->merchant_id?$variables->merchant_id:'',array('class'=>'form-control')); ?>
            </div>
        </div>

        <!--Mellat Gateway Information-->
        <div id="information-mellat" class="collapse-box">
            <div class="form-group">
                <?php echo CHtml::label('کد درگاه ملت',''); ?>
                <?php echo CHtml::textField("SiteSetting[$gateway_variables->name][terminalId]",$variables && $variables->terminalId?$variables->terminalId:'',array('size'=>60,'class'=>'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::label('نام کاربری درگاه',''); ?>
                <?php echo CHtml::textField("SiteSetting[$gateway_variables->name][userName]",$variables && $variables->userName?$variables->userName:'',array('size'=>60,'class'=>'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::label('کلمه عبور درگاه',''); ?>
                <?php echo CHtml::textField("SiteSetting[$gateway_variables->name][userPassword]",$variables && $variables->userPassword?$variables->userPassword:'',array('size'=>60,'class'=>'form-control')); ?>
            </div>
        </div>


        <div class="form-group buttons">
            <?php echo CHtml::submitButton('ذخیره',array('class' => 'btn btn-success')); ?>
        </div>
        <?
        $this->endWidget();
        ?>
    </div>
</div>

<script>
    $(function () {

        var val = $("#SiteSetting_gateway_active").val();
        $('.collapse-box').hide();
        $("#information-" + val).show();

        $("body").on('change', '#SiteSetting_gateway_active', function () {
            var val = $("#SiteSetting_gateway_active").val();
            $('.collapse-box:not(#' + val + ')').hide();
            $("#information-" + val).show();
        })
    })
</script>