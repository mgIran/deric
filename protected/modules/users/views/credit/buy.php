<?php
/* @var $this CreditController */
/* @var $model Users */
/* @var $amounts Array */
?>

<div class="form">

<?php echo CHtml::beginForm($this->createUrl('/users/credit/bill'));?>

    <?php if(Yii::app()->user->hasFlash('min_credit_fail')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <h3>اعتبار کافی نیست!</h3>
        <?php echo Yii::app()->user->getFlash('min_credit_fail');?>
    </div>
    <?php endif;?>

    <h3 class="page-name">خرید اعتبار</h3>
    <p>میزان اعتبار مورد نظر را انتخاب کنید:</p>
    <div class="form-group">
        <?php echo CHtml::radioButtonList('amount', '5000', $amounts);?>
    </div>
    <div class="buttons form-group">
        <?php echo CHtml::submitButton('خرید', array('class'=>'btn btn-success'));?>
        <?php echo CHtml::link('بازگشت',$this->createUrl('/dashboard'), array('class'=>'btn btn-info '));?>
    </div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->