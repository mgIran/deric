<?php
/* @var $this PanelController */
/* @var $appsDataProvider CActiveDataProvider */
/* @var $apps [] */
?>
<div class="dashbord container-fluid">
    <div class="dashbord-header">
        <span class="glyphicon left-icon"></span>
        <h3><strong>تخفیفات</strong></h3>
        <a class="btn btn-primary pull-left" data-toggle="modal" href="#discount-modal"><i class="icon icon-plus"></i> افزودن تخفیف جدید</a>
    </div>
    <div class="dashbord-body">
        <div class="tab-content card-container">
            <div class="tab-pane active">
                <?php $this->renderPartial('//layouts/_flashMessage', array('prefix'=>'discount-'));?>
                <?php
                if($appsDataProvider->totalItemCount):
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>عنوان برنامه</th>
                                <th>وضعیت</th>
                                <th>قیمت</th>
                                <th>درصد</th>
                                <th>قیمت با تخفیف</th>
                                <th>مدت تخفیف</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($appsDataProvider->getData() as $data) $this->renderPartial('_app_discount_list', compact('data'))?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>نتیجه ای یافت نشد</p>
                <?php endif;?>
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