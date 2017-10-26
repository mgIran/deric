<?php
/* @var $this AppsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $title String */
/* @var $pageTitle String */
?>

<div class="app-box">
    <div class="top-box">
        <div class="title pull-right">
            <h2>تخفیفات</h2>
        </div>
    </div>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'id'=>'newest-programs',
        'itemView'=>'//site/_app_discount_item',
        'template'=>'{items}',
        'itemsCssClass'=>'app-carousel'
    ));?>
</div>