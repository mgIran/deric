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
            <h2 style="font-size: 23px;">رایگان ها</h2>
        </div>
    </div>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$free,
        'id'=>'newest-programs',
        'itemView'=>'//site/_app_item',
        'template'=>'{items}',
        'itemsCssClass'=>'app-carousel'
    ));?>
</div>