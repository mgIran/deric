<?php /* @var $model Apps */ ?>

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buy-box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">لیست نظرات</h3>
        </div>
        <div class="panel-body step-content">
            <div class="container-fluid">
                <?php $this->widget('comments.widgets.ECommentsListWidget', array(
                    'model' => $model,
                )); ?>
            </div>
        </div>
    </div>
</div>