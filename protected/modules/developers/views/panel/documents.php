<?php
/* @var $this PanelController */
/* @var $documentsProvider CActiveDataProvider */
?>
<div class="dashbord container-fluid">
    <div class="dashbord-header">
        <span class="glyphicon left-icon"></span>
        <h3><strong>مستندات</strong></h3>
    </div>
    <div class="dashbord-body">
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
</div>