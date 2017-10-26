<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-md-3">
        <?php echo CHtml::label('توسعه دهنده', 'developer');?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::label('از تاریخ', 'from_date');?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::label('تا تاریخ', 'to_date');?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?php echo CHtml::dropDownList('developer', '', CHtml::listData(Users::model()->getDeveloers()->getData(),'id','userDetails.fa_name'));?>
    </div>
    <div class="col-md-3">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'from_date_developer',
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-3">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'to_date_developer',
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-by-developer',
            'id'=>'show-chart-by-developer',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>