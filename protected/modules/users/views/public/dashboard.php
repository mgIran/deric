<?php
/* @var $this PublicController */
/* @var $model User */
$tab ='credit-tab';
if(isset($_GET['tab']))
    $tab = $_GET['tab'];
?>
<div class="dashbord container-fluid">
    <div id="credit-tab" class="tab-pane fade <?= ($tab && $tab=="credit-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>داشبورد</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_credit',array(
                'model'=>$model,
            ))?>
        </div>
    </div>
    <div id="transactions-tab" class="tab-pane fade <?= ($tab && $tab=="transactions-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>لیست تراکنش ها</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_transactions',array(
                'model'=>$model,
            ))?>
        </div>
    </div>
    <div id="buys-tab" class="tab-pane fade <?= ($tab && $tab=="buys-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>لیست خرید ها</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_buys_list',array(
                'model'=>$model,
            ))?>
        </div>
    </div>

    <div id="bookmarks-tab" class="tab-pane fade <?= ($tab && $tab=="profile-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>تغییر مشخصات کاربری</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_profile',array(
                'model'=>$model,
            ))?>
        </div>
    </div>

    <div id="setting-tab" class="tab-pane fade <?= ($tab && $tab=="setting-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>تغییر کلمه عبور</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_setting',array(
                'model'=>$model,
            ))?>
        </div>
    </div>
    <div id="bookmarks-tab" class="tab-pane fade <?= ($tab && $tab=="bookmarks-tab"?'in active':'hidden') ?>">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>نشان شده ها</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('_bookmarks',array(
                'model'=>$model,
            ))?>
        </div>
    </div>
</div>