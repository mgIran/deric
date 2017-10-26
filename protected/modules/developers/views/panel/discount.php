<?php
/* @var $this PanelController */
/* @var $appsDataProvider CActiveDataProvider */
/* @var $apps [] */
?>
<div class="dashboard-container">
    <h3 class="page-name">تخفیفات</h3>
    <div class="tab-content card-container">
        <div class="tab-pane active">
            <?php $this->renderPartial('//layouts/_flashMessage', array('prefix'=>'discount-'));?>
            <div class="overflow-fix">
                <a class="btn btn-success pull-left" data-toggle="modal" href="#discount-modal"><i class="icon icon-plus"></i> افزودن تخفیف جدید</a>
            </div>
            <div class="table text-center">
                <div class="thead">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">عنوان برنامه</div>
                    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs">وضعیت</div>
                    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">قیمت</div>
                    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs">درصد</div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">قیمت با تخفیف</div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">مدت تخفیف</div>
                </div>
                <div class="tbody">
                    <?php $this->widget('zii.widgets.CListView', array(
                        'dataProvider'=>$appsDataProvider,
                        'itemView'=>'_app_discount_list',
                        'template'=>'{items}'
                    ));?>
                </div>
            </div>
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