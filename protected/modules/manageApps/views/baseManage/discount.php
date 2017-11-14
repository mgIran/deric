<?php
/* @var $this BaseManageController */
/* @var $appsDataProvider CActiveDataProvider */
/* @var $apps [] */

$this->breadcrumbs=array(
    'مدیریت تخفیفات '.$this->controller,
);

?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">تخفیفات <?= $this->controller ?></h3>
        <a data-toggle="modal" href="#discount-modal" class="btn btn-default btn-sm">افزودن تخفیف جدید</a>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('//layouts/_flashMessage', array('prefix'=>'discount-'));?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'discounts-grid',
                'dataProvider'=>$appsDataProvider,
                'columns'=>array(
                    [
                        'header' => 'عنوان برنامه',
                        'value' => function($data){
                            return CHtml::link($data->app->title, $data->app->getViewUrl());
                        },
                        'type' => 'raw'
                    ],
                    [
                        'header' => 'وضعیت',
                        'value' => function($data){
                            return $data->app->status=='enable'?'فعال':'غیر فعال';
                        }
                    ],
                    [
                        'header' => 'قیمت',
                        'value' => function($data){
                            return ($data->app->price==0)?'رایگان':Controller::parseNumbers(number_format($data->app->price,0)).' تومان';
                        }
                    ],
                    [
                        'header' => 'درصد',
                        'value' => 'Controller::parseNumbers($data->percent).\'%\''
                    ],
                    [
                        'header' => 'قیمت با تخفیف',
                        'value' => 'Controller::parseNumbers(number_format($data->offPrice)).\' تومان\''
                    ],
                    [
                        'header' => 'مدت تخفیف',
                        'value' => function($data){
                            return Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->start_date)).'<br>الی<br>'.Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->end_date));
                        }
                    ],
                    array(
                        'class'=>'CButtonColumn',
                        'buttons' => array(
                            'delete' => array(
                                'url' => 'Yii::app()->createUrl("/manageApps/'.$this->controller.'/deleteDiscount", array("id"=>$data->id))'
                            )
                        )
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

<div id="discount-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" >&times;</button>
                <h5>افزودن تخیف</h5>
            </div>
            <div class="modal-body">
                <? $this->renderPartial('_discount_form',array('model' => new AppDiscounts(),'apps' => $apps)); ?>
            </div>
        </div>
    </div>
</div>