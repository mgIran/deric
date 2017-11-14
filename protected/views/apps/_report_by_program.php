<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<h4>برنامه مورد نظر را انتخاب کنید:</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>new CActiveDataProvider('Apps',array(
                'criteria' => array(
                    'condition' => 'title IS NOT NULL AND deleted = 0'
                )
            )),
            'itemView'=>'_report_sale_app_list',
            'template'=>'{items}'
        ));?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php echo CHtml::label('از تاریخ', 'from_date');?>
    </div>
    <div class="col-md-4">
        <?php echo CHtml::label('تا تاریخ', 'to_date');?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'from_date',
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'to_date',
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-4">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-by-program',
            'id'=>'show-chart-by-program',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>
<?php Yii::app()->clientScript->registerScript('submitReport', "
    $('#show-chart-by-program').click(function(){
        if($('input[name=\"app_id\"]:checked').length==0){
            alert('لطفا برنامه مورد نظر خود را انتخاب کنید.');
            return false;
        }
    });
");

$ss = explode('/', JalaliDate::date("Y/m/d/H/i/s", isset($_POST['from_date_altField'])?$_POST['from_date_altField']:time(), false));
$es = explode('/', JalaliDate::date("Y/m/d/H/i/s", isset($_POST['to_date_altField'])?$_POST['to_date_altField']:time(), false));
Yii::app()->clientScript->registerScript('dateSets', '
    $("#from_date").persianDatepicker("setDate",['.$ss[0].','.$ss[1].','.$ss[2].','.$ss[3].','.$ss[4].','.$ss[5].']);
    $("#to_date").persianDatepicker("setDate",['.$es[0].','.$es[1].','.$es[2].','.$es[3].',00,00]);
',CClientScript::POS_READY);
?>