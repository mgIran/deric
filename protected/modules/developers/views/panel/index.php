<?php
/* @var $this PanelController */
/* @var $appsDataProvider CActiveDataProvider */
?>
<div class="dashbord container-fluid">
    <div class="dashbord-header">
        <span class="glyphicon left-icon"></span>
        <h3><strong>برنامه ها</strong></h3>
        <a class="btn btn-primary pull-left" href="<?php echo $this->createUrl('/developers/apps/create');?>"><i class="icon icon-plus"></i> افزودن برنامه جدید</a>
    </div>
    <div class="dashbord-body">
        <?php $this->renderPartial('//layouts/_flashMessage', array('prefix'=>'images-'));?>
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
                    <th>تعداد نصب شده</th>
                    <th>عملیات</th>
                    <th>تاییدیه</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($appsDataProvider->getData() as $data) $this->renderPartial('_app_list', compact('data'))?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p>نتیجه ای یافت نشد</p>
        <?php endif;?>
    </div>
</div>