<?php
$purifier=new CHtmlPurifier();
/* @var $this PagesManageController*/
/* @var $model Pages */
?>
<div class="dashbord container-fluid">
    <div class="dashbord-header">
        <span class="glyphicon left-icon"></span>
        <h3><strong>برنامه ها</strong></h3>
    </div>
    <div class="dashbord-body">
        <div class="card-container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5><?= $model->title ?></h5>
                </div>
                <div class="panel-body">
                    <p dir="auto" class="text-justify"><?= $purifier->purify($model->summary) ?></p>
                </div>
            </div>
            <p class="text-left"><a href="<?php echo $this->createUrl('/developers/panel/documents');?>" class="btn btn-info">بازگشت</a></p>
        </div>
    </div>
</div>