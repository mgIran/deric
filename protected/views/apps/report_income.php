<?php
/* @var $this AppsController */
/* @var $labels array */
/* @var $values array */
/* @var $showChart boolean */
/* @var $sumIncome integer */
/* @var $sumCredit integer */

Yii::app()->clientScript->registerCss('appsStyle','
.report-sale .app-item:nth-child(n+3){
    margin-top: 50px;
}
.report-sale .app-item input[type="radio"]{
    float: right;
    margin-top: 27px;
    margin-left: 15px;
}
.report-sale .app-item img{
    float: right;
    max-width: 70px;
    max-height:70px;
    height:auto;
    margin-left: 15px;
}
.report-canvas{
    margin-top: 50px;
    margin-bottom: 50px;
}
.chart-container{
    margin-top: 50px;
}
.report-sale .panel{
    border: 1px solid #ccc;
}
');
?>
<div class="box">
    <div class="box-header with-border"><h3 class="box-title">گزارش درآمد</h3></div>
    <div class="box-body">
        <?php $this->renderPartial('_report_monthly', array('labels'=>$labels,'values'=>$values)); ?>
        <p style="margin-top: 50px;"><b>مجموع اعتبار کاربران: </b><?php echo Controller::parseNumbers(number_format($sumCredit));?> تومان</p>
        <small>این مبلغ به صورت موقت در حساب بانکی شما می باشد.</small>
    </div>
</div>

<?php if($showChart):?>
    <div class="box">
        <div class="box-header with-border"><h3 class="box-title"></h3></div>
        <div class="box-body chart">
            <p><b>مجموع در آمد این ماه: </b><?php echo Controller::parseNumbers(number_format($sumIncome));?> تومان</p>
            <?php $this->widget(
                'chartjs.widgets.ChBars',
                array(
                    'width' => 300,
                    'height' => 250,
                    'labels' => $labels,
                    'datasets' => array(
                        array(
                            "fillColor" => "rgba(54, 162, 235, 0.5)",
                            "strokeColor" => "rgba(54, 162, 235, 1)",
                            "data" => $values
                        )
                    ),
                    'options' => '{
                            showScale: true,
                            scaleShowGridLines: false,
                            scaleGridLineColor: "rgba(0,0,0,.05)",
                            scaleGridLineWidth: 1,
                            scaleShowHorizontalLines: true,
                            scaleShowVerticalLines: true,
                            bezierCurve: false,
                            bezierCurveTension: 0.3,
                            pointDot: false,
                            pointDotRadius: 4,
                            pointDotStrokeWidth: 1,
                            pointHitDetectionRadius: 20,
                            datasetStroke: true,
                            datasetStrokeWidth: 2,
                            datasetFill: true,
                            legend: {
                                display: true,
                                labels: {
                                    fontColor: \'rgb(255, 99, 132)\'
                                }
                            },
                            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                            maintainAspectRatio: true,
                            responsive: true
                        }'
                )
            );?>
        </div>
    </div>
<?php endif;?>