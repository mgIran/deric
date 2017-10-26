<?php
/* @var $this PublicController */
/* @var $model Users */
$tab ='credit-tab';
if(isset($_GET['tab']))
    $tab = $_GET['tab'];
?>
<div class="dashboard-container">
    <div id="credit-tab" class="tab-pane fade <?= ($tab && $tab=="credit-tab"?'in active':'hidden') ?>">
        <h3 class="page-name">داشبورد</h3>
        <?php $this->renderPartial('_credit',array(
            'model'=>$model,
        ))?>
    </div>
    <div id="transactions-tab" class="tab-pane fade <?= ($tab && $tab=="transactions-tab"?'in active':'hidden') ?>">
        <h3 class="page-name">لیست تراکنش ها</h3>
        <?php $this->renderPartial('_transactions',array(
            'model'=>$model,
        ))?>
    </div>
    <div id="buys-tab" class="tab-pane fade <?= ($tab && $tab=="buys-tab"?'in active':'hidden') ?>">
        <h3 class="page-name">لیست خرید ها</h3>
        <?php $this->renderPartial('_buys_list',array(
            'model'=>$model,
        ))?>
    </div>
    <div id="setting-tab" class="tab-pane fade <?= ($tab && $tab=="setting-tab"?'in active':'hidden') ?>">
        <h3 class="page-name">تنظیمات</h3>
        <h4 class="col-lg-12 col-md-12 col-sm-12 col-xs-12">تغییر کلمه عبور</h4>
        <?php $this->renderPartial('_setting',array(
            'model'=>$model,
        ))?>
    </div>
    <div id="bookmarks-tab" class="tab-pane fade <?= ($tab && $tab=="bookmarks-tab"?'in active':'hidden') ?>">
        <h3 class="page-name">نشان شده ها</h3>
        <?php $this->renderPartial('_bookmarks',array(
            'model'=>$model,
        ))?>
    </div>
</div>