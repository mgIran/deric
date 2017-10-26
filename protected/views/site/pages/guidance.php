<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle= Yii::app()->name . ' - راهنما و پشتیبانی';
$this->breadcrumbs=array(
    'راهنما و پشتیبانی'=>array(''),
);
?>
<div class="page rtl col-lg-12 col-md-12 col-sm-12 col-xs-12 index" >
    <div class="panel-body">
        <h2 class="page-header">
    راهنما
        </h2>
            <?php
            $this->widget('zii.widgets.CListView', array(
                'id' => 'guide-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_view',
                'template' => '{items} {pager}',
                'ajaxUpdate' => true,
                'pager' => array(
                    'class' => 'ext.infiniteScroll.IasPager',
                    'rowSelector'=>'.text-item',
                    'listViewId' => 'guide-list',
                    'header' => '',
                    'loaderText'=>'در حال دریافت ...',
                    'options' => array('history' => false, 'triggerPageTreshold' => 3, 'trigger'=>'بیشتر'),
                ),
                'afterAjaxUpdate'=>"function(id, data) {
                    $.ias({
                        'history': false,
                        'triggerPageTreshold': 3,
                        'trigger': 'بیشتر',
                        'container': '#guide-list',
                        'item': '.text-item',
                        'pagination': '#guide-list .pager',
                        'next': '#guide-list .next:not(.disabled):not(.hidden) a',
                        'loader': 'در حال دریافت ...'
                    });
                }",
            ));
            ?>

        </div>
    </div>