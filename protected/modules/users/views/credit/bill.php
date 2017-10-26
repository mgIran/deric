<?php
/* @var $this CreditController */
/* @var $amount string */
/* @var $model Users */
?>

<h3 class="page-name">خرید اعتبار</h3>
<div class="panel panel-primary">
    <div class="panel-heading">پیش فاکتور</div>
    <div class="panel-body">
        <?php $this->renderPartial('//layouts/_flashMessage');?>
        <div class="col-md-6">
            <h4>اطلاعات پرداخت</h4>
            <div class="panel-body">
                <p>
                    <?php echo CHtml::label('اعتبار فعلی شما: ','');?>
                    <?php echo number_format($model->userDetails->credit, 0).' تومان';?>
                </p>
                <p>
                    <?php echo CHtml::label('اعتبار درخواستی: ','');?>
                    <?php echo number_format($amount, 0).' تومان';?>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel-body">
                <h4>
                    روش پرداخت
                    <?php echo CHtml::link('بازگشت',$this->createUrl('/dashboard'), array('class'=>'btn btn-info pull-left'));?>
                </h4>
                <?php echo CHtml::beginForm($this->createUrl('/users/credit/bill'));?>
                    <?php echo CHtml::hiddenField('amount', CHtml::encode($_POST['amount']));?>
                    <h5>درگاه <?php
                        switch($this->active_gateway){
                            case 'mellat':
                                echo 'بانک ملت';
                                break;
                            case 'zarinpal':
                                echo 'زرین پال';
                                break;
                        }
                        ?></h5>
                    <span class="h5"><small>کلیه کارت های عضو شتاب</small></span>
                    <?php echo CHtml::submitButton('پرداخت', array(
                        'class'=>'btn btn-success pull-left',
                        'name'=>'pay',
                    ));?>
                <?php echo CHtml::endForm();?>
            </div>
        </div>
    </div>
</div>