<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-md-4">
        <?php echo CHtml::label('ماه مورد نظر را انتخاب کنید:', 'month');?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'month',
            'options'=>array(
                'format'=>'MMMM YYYY',
                'monthPicker'=>'js:{enabled:true}',
                'dayPicker'=>'js:{enabled:false}',
                'yearPicker'=>'js:{enabled:false}',
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-4">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-monthly',
            'id'=>'show-chart-monthly',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>
<?php
$ss = explode('/', JalaliDate::date("Y/m/d/H/i/s", isset($_POST['month_altField'])?$_POST['month_altField']:time(), false));
Yii::app()->clientScript->registerScript('monthSets', '
    $("#month").persianDatepicker("setDate",['.$ss[0].','.$ss[1].','.$ss[2].','.$ss[3].','.$ss[4].','.$ss[5].']);
',CClientScript::POS_READY);
