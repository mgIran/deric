<?php
/* @var $this DashboardController*/
/* @var $devIDRequests CActiveDataProvider*/
/* @var $newestPrograms CActiveDataProvider*/
/* @var $newestDevelopers CActiveDataProvider*/
/* @var $newestPackages CActiveDataProvider*/
/* @var $updatedPackages CActiveDataProvider*/
/* @var $statistics []*/
/* @var $todaySales []*/
$permissions = [
    'devIDRequests' => false,
    'newestPrograms' => false,
    'newestDevelopers' => false,
    'newestPackages' => false,
    'updatedPackages' => false,
    'statistics' => false,
    'todaySales' => false,
    'AppStatistics' => false,
    'DevStatistics' => false,
    'TransactionStatistics' => false,
    'TicketStatistics' => false,
];
if(Yii::app()->user->roles == 'admin'){
    $permissions['devIDRequests'] = true;
    $permissions['newestPrograms'] = true;
    $permissions['newestDevelopers'] = true;
    $permissions['newestPackages'] = true;
    $permissions['updatedPackages'] = true;
    $permissions['statistics'] = true;
    $permissions['todaySales'] = true;
    $permissions['AppStatistics'] = true;
    $permissions['DevStatistics'] = true;
    $permissions['TransactionStatistics'] = true;
    $permissions['TicketStatistics'] = true;
}

if(Yii::app()->user->roles == 'validator'){
    $permissions['newestPrograms'] = true;
    $permissions['newestPackages'] = true;
    $permissions['updatedPackages'] = true;
    $permissions['AppStatistics'] = true;
}

if(Yii::app()->user->roles == 'supporter'){
    $permissions['statistics'] = true;
    $permissions['TicketStatistics'] = true;
}


if(Yii::app()->user->roles == 'finance'){
    $permissions['statistics'] = true;
}


if(Yii::app()->user->roles == 'employee'){
    $permissions['statistics'] = true;
    $permissions['AppStatistics'] = true;
}

?>
<div class="row boxed-statistics">
    <!--Apps Statistics-->
    <?php
    if($permissions['AppStatistics']):
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo $statistics['apps'];?></h3>
                <p>اپلیکیشن ها</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-apps"></i>
            </div>
            <a href="<?php echo $this->createUrl('/manageApps/android/admin');?>" class="small-box-footer">مشاهده لیست <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <?php
    endif;
    ?>
    <!--Developers Statistics-->
    <?php
    if($permissions['DevStatistics']):
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo $statistics['developers'];?></h3>
                <p>توسعه دهندگان</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $this->createUrl('/users/manage');?>" class="small-box-footer">مشاهده لیست <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
        <?php
    endif;
    ?>
    <!--Transaction Statistics-->
    <?php
    if($permissions['TransactionStatistics']):
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3><?php echo $statistics['transactions'];?></h3>
                <p>پرداخت ها</p>
            </div>
            <div class="icon">
                <i class="ion ion-cash"></i>
            </div>
            <a href="<?php echo $this->createUrl('/apps/reportSales');?>" class="small-box-footer">مشاهده لیست <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
        <?php
    endif;
    ?>
    <!--Ticket Statistics-->
    <?php
    if($permissions['TicketStatistics']):
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $statistics['tickets'];?></h3>
                <p>پشتیبانی</p>
            </div>
            <div class="icon">
                <i class="ion ion-headphone"></i>
            </div>
            <a href="<?php echo $this->createUrl('/tickets/manage/admin');?>" class="small-box-footer">مشاهده لیست <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
        <?php
    endif;
    ?>
</div>
<div class="row">
    <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Newest Apps -->
        <?php
        if($permissions['newestPrograms']):
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" >جدیدترین نرم افزار ها</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'newest-apps-grid',
                        'dataProvider'=>$newestPrograms,
                        'itemsCssClass' => 'table table-hover',
                        'columns'=>array(
                            'title',
                            'developer_id'=>array(
                                'name'=>'developer_id',
                                'value'=>'(is_null($data->developer_id) or empty($data->developer_id))?$data->developer_team:$data->developer->userDetails->developer_id'
                            ),
                            'platform_id'=>array(
                                'name'=>'platform_id',
                                'value'=>'$data->platform->title'
                            ),
                            'price'=>array(
                                'name'=>'price',
                                'value'=>'($data->price==0)?"رایگان":number_format($data->price, 0)." تومان"'
                            ),
                            'category_id'=>array(
                                'name'=>'category_id',
                                'value'=>'$data->category->title'
                            ),
                            'status'=>array(
                                'name'=>'status',
                                'value'=>'$data->statusLabels[$data->status]'
                            ),
                            'confirm'=>array(
                                'name'=>'confirm',
                                'value'=>'CHtml::dropDownList("confirm", "pending", $data->confirmLabels, array("class"=>"change-confirm", "data-id"=>$data->id))',
                                'type'=>'raw'
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{view} {delete} {download}',
                                'buttons'=>array(
                                    'view'=>array(
                                        'label'=>'مشاهده برنامه',
                                        'url'=>'Yii::app()->createUrl("/apps/".$data->id."/".urlencode($data->title))',
                                        'options'=>array(
                                            'target'=>'_blank'
                                        ),
                                        'visible' => '$data->lastPackage'
                                    ),
                                    'delete'=>array(
                                        'url'=>'CHtml::normalizeUrl(array(\'/manageApps/\'.$data->platformsID[$data->platform_id].\'/delete/\'.$data->id))'
                                    ),
                                    'download'=>array(
                                        'label'=>'دانلود',
                                        'url'=>'Yii::app()->createUrl("/manageApps/android/download/".$data->id)',
                                        'imageUrl'=>false,
                                        'options' => array('class' => 'btn btn-success btn-sm')
                                    ),
                                ),
                            ),
                        ),
                    ));?>
                </div>
                <?php Yii::app()->clientScript->registerScript('changeConfirm', "
                    $('.change-confirm').on('change', function(){
                        $.ajax({
                            url:'".$this->createUrl('/manageApps/android/changeConfirm')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{app_id:$(this).data('id'), value:$(this).val()},
                            success:function(data){
                                if(data.status){
                                    $.fn.yiiGridView.update('newest-apps-grid');
                                    $.fn.yiiGridView.update('newest-packages-grid');
                                    $.fn.yiiGridView.update('updated-packages-grid');
                                }else
                                    alert('در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                            }
                        });
                    });
                ");?>
            </div>
        </div>
        <?php
        endif;
        ?>
    </section>
    <section class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!--Newest Packages-->
        <?php
        if($permissions['newestPackages']):
            ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title" >بسته های جدید</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'newest-packages-grid',
                        'dataProvider'=>$newestPackages,
                        'itemsCssClass' => 'table table-hover',
                        'columns'=>array(
                            'app_id'=>array(
                                'name'=>'app_id',
                                'value'=>'CHtml::link($data->app->title, Yii::app()->createUrl("/apps/".$data->app_id."/".$data->app->title))',
                                'type'=>'raw'
                            ),
                            'version',
                            'package_name',
                            'status'=>array(
                                'name'=>'status',
                                'value'=>'CHtml::dropDownList("confirm", "pending", $data->statusLabels, array("class"=>"change-package-status", "data-id"=>$data->id))',
                                'type'=>'raw'
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{delete} {download}',
                                'buttons'=>array(
                                    'delete'=>array(
                                        'url'=>'Yii::app()->createUrl("/manageApps/android/deletePackage/".$data->id)'
                                    ),
                                    'download'=>array(
                                        'label'=>'دانلود',
                                        'url'=>'Yii::app()->createUrl("/manageApps/android/downloadPackage/".$data->id)',
                                        'imageUrl'=>false,
                                        'options' => array('class' => 'btn btn-success btn-sm')
                                    ),
                                ),
                            ),
                        ),
                    ));?>
                </div>
            </div>
        </div>
        <?php
        endif;
        ?>
        <!--Updated Developer ID-->
        <?php
        if($permissions['devIDRequests']):
        ?>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">درخواست های تغییر شناسه توسعه دهنده</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'dev-id-requests-grid',
                        'dataProvider'=>$devIDRequests,
                        'itemsCssClass' => 'table table-hover',
                        'columns'=>array(
                            'user_id'=>array(
                                'name'=>'user_id',
                                'value'=>'CHtml::link($data->user->userDetails->fa_name, Yii::app()->createUrl("/users/".$data->user->id))',
                                'type'=>'raw'
                            ),
                            'requested_id',
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{confirm} {delete}',
                                'buttons'=>array(
                                    'confirm'=>array(
                                        'label'=>'تایید کردن',
                                        'url'=>"CHtml::normalizeUrl(array('/users/usersManage/confirmDevID', 'id'=>\$data->user_id))",
                                        'imageUrl'=>Yii::app()->theme->baseUrl.'/img/confirm.png',
                                    ),
                                    'delete'=>array(
                                        'url'=>'CHtml::normalizeUrl(array(\'/users/usersManage/deleteDevID\', \'id\'=>$data->user_id))',
                                    ),
                                ),
                            ),
                        ),
                    ));?>
                </div>
            </div>
        </div>
            <?php
        endif;
        ?>
        <!--Statistics-->
        <?php
        if($permissions['statistics']):
        ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title" >آمار بازدیدکنندگان</h3>
            </div>
            <div class="box-body">
                <p>
                    افراد آنلاین : <?php echo Yii::app()->userCounter->getOnline(); ?><br />
                    بازدید امروز : <?php echo Yii::app()->userCounter->getToday(); ?><br />
                    بازدید دیروز : <?php echo Yii::app()->userCounter->getYesterday(); ?><br />
                    تعداد کل بازدید ها : <?php echo Yii::app()->userCounter->getTotal(); ?><br />
                    بیشترین بازدید : <?php echo Yii::app()->userCounter->getMaximal(); ?><br />
                </p>
            </div>
        </div>
            <?php
        endif;
        ?>
    </section>
    <section class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!--Updated Packages-->
        <?php
        if($permissions['updatedPackages']):
        ?>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title" >بسته های به روز شده</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'updated-packages-grid',
                        'dataProvider'=>$updatedPackages,
                        'itemsCssClass' => 'table table-hover',
                        'columns'=>array(
                            'app_id'=>array(
                                'name'=>'app_id',
                                'value'=>'CHtml::link($data->app->title, Yii::app()->createUrl("/apps/".$data->app_id."/".$data->app->title))',
                                'type'=>'raw'
                            ),
                            'version',
                            'package_name',
                            'status'=>array(
                                'name'=>'status',
                                'value'=>'CHtml::dropDownList("confirm", "pending", $data->statusLabels, array("class"=>"change-package-status", "data-id"=>$data->id))',
                                'type'=>'raw'
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{delete} {download}',
                                'buttons'=>array(
                                    'delete'=>array(
                                        'label'=>'',
                                        'url'=>'Yii::app()->createUrl("/manageApps/android/deletePackage/".$data->id)'
                                    ),
                                    'download'=>array(
                                        'label'=>'دانلود',
                                        'url'=>'Yii::app()->createUrl("/manageApps/android/downloadPackage/".$data->id)',
                                        'imageUrl'=>false,
                                        'options' => array('class' => 'btn btn-success btn-sm')
                                    ),
                                ),
                            ),
                        ),
                    ));?>
                </div>
                <?php Yii::app()->clientScript->registerScript('changePackageStatus', "
                $('body').on('change', '.change-package-status', function(){
                    if($(this).val()=='refused' || $(this).val()=='change_required'){
                        $('#reason-modal').modal('show');
                        $('input#package-id').val($(this).data('id'));
                        $('input#package-status').val($(this).val());
                    }else{
                        $.ajax({
                            url:'".$this->createUrl('/manageApps/android/changePackageStatus')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{package_id:$(this).data('id'), value:$(this).val()},
                            success:function(data){
                                if(data.status){
                                    $.fn.yiiGridView.update('newest-packages-grid');
                                    $.fn.yiiGridView.update('updated-packages-grid');
                                }else
                                    alert('در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                            }
                        });
                    }
                });
                $('.close-reason-modal').click(function(){
                    $.fn.yiiGridView.update('newest-packages-grid');
                    $.fn.yiiGridView.update('updated-packages-grid');
                    $('#reason-text').val('');
                });
                $('.save-reason-modal').click(function(){
                    if($('#reason-text').val()==''){
                        $('.reason-modal-message').addClass('error').text('لطفا دلیل را ذکر کنید.');
                        return false;
                    }else{
                        $('.reason-modal-message').removeClass('error').text('در حال ثبت...');
                        $.ajax({
                            url:'".$this->createUrl('/manageApps/android/changePackageStatus')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{package_id:$('#package-id').val(), value:$('#package-status').val(), reason:$('#reason-text').val()},
                            success:function(data){
                                if(data.status){
                                    $.fn.yiiGridView.update('newest-packages-grid');
                                    $.fn.yiiGridView.update('updated-packages-grid');
                                    $('#reason-modal').modal('hide');
                                    $('#reason-text').val('');
                                    $('.reason-modal-message').text('');
                                } else
                                    alert('در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                            }
                        });
                    }
                });
            ");?>
            </div>
        </div>
            <?php
        endif;
        ?>
        <!--Newest Developers-->
        <?php
        if($permissions['newestDevelopers']):
        ?>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">اطلاعات توسعه دهندگان جدید<small>(تایید نشده)</small></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'newest-developers-grid',
                        'dataProvider'=>$newestDevelopers,
                        'itemsCssClass' => 'table table-hover',
                        'columns'=>array(
                            'email'=>array(
                                'name'=>'email',
                                'value'=>'CHtml::link($data->user->email, Yii::app()->createUrl("/users/".$data->user_id))',
                                'type'=>'raw'
                            ),
                            'fa_name',
                            array(
                                'class'=>'CButtonColumn',
                                'template' => '{view} {confirm} {refused}',
                                'buttons'=>array(
                                    'confirm'=>array(
                                        'label'=>'تایید کردن',
                                        'url'=>"CHtml::normalizeUrl(array('/users/usersManage/confirmDeveloper', 'id'=>\$data->user_id))",
                                        'imageUrl'=>Yii::app()->theme->baseUrl.'/img/confirm.png',
                                    ),
                                    'refused'=>array(
                                        'label'=>'رد کردن',
                                        'url'=>'CHtml::normalizeUrl(array(\'/users/usersManage/refuseDeveloper\', \'id\'=>$data->user_id))',
                                        'imageUrl'=>Yii::app()->theme->baseUrl.'/img/refused.png',
                                    ),
                                    'view'=>array(
                                        'url'=>'CHtml::normalizeUrl(array("/users/".$data->user_id))',
                                    ),
                                ),
                            ),
                        ),
                    ));?>
                </div>
            </div>
        </div>
            <?php
        endif;
        ?>
        <!--Today Sales-->
        <?php
        if($permissions['todaySales']):
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">فروش امروز</h3>
            </div>
            <div class="box-body">
                <div class="chart text-center">
                    <?php $this->widget('chartjs.widgets.ChLine', array(
                        'width' => 510,
                        'height' => 250,
                        'labels' => $todaySales['labels'],
                        'datasets' => array(
                            array(
                                "fillColor" => "rgba(60,141,188,0.9)",
                                "strokeColor" => "rgba(60,141,188,0.8)",
                                "pointColor" => "#3b8bba",
                                "pointStrokeColor" => "rgba(60,141,188,1)",
                                "pointHighlightFill" => "#fff",
                                "pointHighlightStroke" => "rgba(60,141,188,1)",
                                "data" => $todaySales['values']
                            )
                        ),
                        'options' => '{
                            showScale: true,
                            scaleShowGridLines: false,
                            scaleGridLineColor: "rgba(0,0,0,.05)",
                            scaleGridLineWidth: 1,
                            scaleShowHorizontalLines: true,
                            scaleShowVerticalLines: true,
                            bezierCurve: true,
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
                    ));?>
                </div>
            </div>
        </div>
            <?php
        endif;
        ?>
    </section>
</div>


<div id="reason-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php echo CHtml::hiddenField('package_id', '', array('id'=>'package-id'));?>
                <?php echo CHtml::hiddenField('package_status', '', array('id'=>'package-status'));?>
                <?php echo CHtml::label('لطفا دلیل این انتخاب را بنویسید:', 'reason-text')?>
                <?php echo CHtml::textArea('reason', '', array('placeholder'=>'دلیل', 'class'=>'form-control', 'id'=>'reason-text'));?>
                <div class="reason-modal-message error"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-reason-modal" data-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-success save-reason-modal">ثبت</button>
            </div>
        </div>
    </div>
</div>