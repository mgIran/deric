<?php
$purifier=new CHtmlPurifier();
/* @var $this PagesManageController*/
/* @var $model Pages */
?>
<div class="dashboard-container">
    <h3 class="page-name">مستندات</h3>
    <div class="card-container">
        <p class="text-left"><a href="<?php echo $this->createUrl('/developers/panel/documents');?>" class="btn btn-info">بازگشت</a></p>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $model->title ?>
            </div>
            <div class="panel-body">
                <p><?= $purifier->purify($model->summary) ?></p>
            </div>
        </div>
        <p class="text-left"><a href="<?php echo $this->createUrl('/developers/panel/documents');?>" class="btn btn-info">بازگشت</a></p>
    </div>
</div>