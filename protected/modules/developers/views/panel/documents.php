<?php
/* @var $this PanelController */
/* @var $documentsProvider CActiveDataProvider */
?>
<div class="dashboard-container">
    <h3 class="page-name">مستندات</h3>
    <div class="card-container">
        <h4>فهرست</h4>
        <ul>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$documentsProvider,
                'itemView'=>'_document_list',
                'template'=>'{items}'
            ));?>
        </ul>
    </div>
</div>