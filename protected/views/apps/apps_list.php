<?php
/* @var $this AppsController */
/* @var $latest CActiveDataProvider */
/* @var $topRates CActiveDataProvider */
/* @var $free CActiveDataProvider */
/* @var $title String */
/* @var $pageTitle String */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
?>

<div class="app-box">
    <div class="top-box">
        <div class="title" style="margin-bottom: 25px;padding-bottom: 25px;border-bottom: 1px solid #ccc;display: block;">
            <h2 style="font-size: 23px;"><?php echo CHtml::encode($pageTitle).((!is_null($title))?'ی '.CHtml::encode($title):null)?></h2>
        </div>
    </div>
    <h4>برترین ها</h4>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$topRates,
        'id'=>'newest-programs',
        'itemView'=>'//site/_app_item',
        'template'=>'{items}',
        'itemsCssClass'=>'app-carousel'
    ));?>
    <h4>تازه ها</h4>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$latest,
        'id'=>'newest-programs',
        'itemView'=>'//site/_app_item',
        'template'=>'{items}',
        'itemsCssClass'=>'app-carousel'
    ));?>
    <h4>رایگان ها</h4>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$free,
        'id'=>'newest-programs',
        'itemView'=>'//site/_app_item',
        'template'=>'{items}',
        'itemsCssClass'=>'app-carousel'
    ));?>
</div>

<?
Yii::app()->clientScript->registerScript('carousels','
    var owl = $(".app-carousel");
    owl.owlCarousel({
        responsive:{
            0:{
                items : 1,
            },
            410:{
                items : 2,
            },
            580:{
                items : 3
            },
            800:{
                items : 4
            },
            1130:{
                items : 5
            },
            1370:{
                items : 6
            }
        },
        lazyLoad :true,
        margin :0,
        rtl:true,
        nav:true,
        navText : ["","<span class=\'icon-chevron-left\'></span>"]
    });

'
);