<?php
/* @var $this PanelController */
/* @var $apps CActiveDataProvider */
/* @var $labels array */
/* @var $values array */
?>

<div class="card-container">
    <h3 class="page-name">گزارش فروش</h3>
    <div class="tab-pane active report-sale">
        <?php echo CHtml::beginForm();?>
            <h4>برنامه مورد نظر را انتخاب کنید:</h4>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php $this->widget('zii.widgets.CListView', array(
                        'dataProvider'=>$apps,
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
                        'name'=>'show-chart',
                        'id'=>'show-chart',
                    ));?>
                </div>
            </div>
            <?php if(isset($_POST['from_date_altField'])):?>
                <div class="panel panel-default chart-container">
                    <div class="panel-body">
                        <h4>نمودار گزارش</h4>
                        <?php $this->widget(
                            'chartjs.widgets.ChBars',
                            array(
                                'width' => 700,
                                'height' => 400,
                                'htmlOptions' => array(
                                    'class'=>'center-block report-canvas'
                                ),
                                'labels' => $labels,
                                'datasets' => array(
                                    array(
                                        "fillColor" => "rgba(54, 162, 235, 0.5)",
                                        "strokeColor" => "rgba(54, 162, 235, 1)",
                                        "data" => $values
                                    )
                                ),
                                'options' => array()
                            )
                        );?>
                    </div>
                </div>
            <?php else:?>
                <div class="panel panel-default chart-container">
                    <div class="panel-body">
                        <h4>فروش امروز</h4>
                        <?php $this->widget('chartjs.widgets.ChBars', array(
                            'width' => 700,
                            'height' => 400,
                            'htmlOptions' => array(
                                'class'=>'center-block report-canvas'
                            ),
                            'labels' => $labels,
                            'datasets' => array(
                                array(
                                    "fillColor" => "rgba(54, 162, 235, 0.5)",
                                    "strokeColor" => "rgba(54, 162, 235, 1)",
                                    "data" => $values
                                )
                            ),
                            'options' => array()
                        ));?>
                    </div>
                </div>
            <?php endif;?>
        <?php echo CHtml::endForm();?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('submitReport', "
    $('#show-chart').click(function(){
        if($('input[name=\"app_id\"]:checked').length==0){
            alert('لطفا برنامه مورد نظر خود را انتخاب کنید.');
            return false;
        }
    });
");?>