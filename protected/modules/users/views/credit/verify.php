<?php
/* @var $this CreditController */
/* @var $model UserTransactions */
/* @var $userDetails UserDetails */
?>

<h3 class="page-name">خرید اعتبار</h3>
<div class="panel panel-primary">
    <div class="panel-heading">جزئیات پرداخت</div>
    <div class="panel-body">
        <?php if(Yii::app()->user->hasFlash('success')):?>
            <div class="alert alert-success fade in">
                <?php echo Yii::app()->user->getFlash('success');?>
            </div>
            <h4>اطلاعات تراکنش</h4>
            <div class="panel-body">
                <p>
                    <?php echo CHtml::label('مبلغ پرداخت شده:','');?>
                    <?php echo number_format($model->amount, 0).' تومان';?>
                </p>
                <p>
                    <?php echo CHtml::label('اعتبار فعلی شما:','');?>
                    <?php echo number_format($userDetails->credit, 0).' تومان';?>
                </p>
                <p>
                    <?php echo CHtml::label('کد رهگیری تراکنش:','');?>
                    <?php echo CHtml::encode($model->token);?>
                </p>
            </div>
        <?php elseif(Yii::app()->user->hasFlash('failed')):?>
            <div class="alert alert-danger fade in">
                <?php echo Yii::app()->user->getFlash('failed');?>
                <?php if(Yii::app()->user->hasFlash('transactionFailed')) echo '<br>'.Yii::app()->user->getFlash('transactionFailed');?>
            </div>
            <div class="panel-body">
                <p class="text-center">
                    <a href="<?php echo $this->createUrl('/users/credit/buy')?>" class="btn btn-danger">خرید مجدد اعتبار</a>
                </p>
            </div>
        <?php endif;?>
    </div>
</div>