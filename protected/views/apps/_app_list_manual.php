<?php
/* @var $this AppsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $title String */
/* @var $pageTitle String */
?>

<div class="app-box">
    <div class="top-box">
        <div class="title" style="margin-bottom: 25px;padding-bottom: 25px;border-bottom: 1px solid #ccc;display: block;">
            <h2 style="font-size: 23px;"><?php echo CHtml::encode($pageTitle).((!is_null($title))?'ی '.CHtml::encode($title):null)?></h2>
        </div>
    </div>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'id'=>'programs-list',
        'itemView'=>'//site/_app_item',
        'template'=>'{items}',
        'itemsCssClass'=>'list'
    ));?>
</div>