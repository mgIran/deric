<?php
/* @var $this PublicController */
/* @var $model Users */
/* @var $transaction UserTransactions */
?>

<div class="container-fluid">
    <?php if(empty($model->transactions)):?>
        نتیجه ای یافت نشد.
    <?php else:?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr class="text-center">
                    <th>زمان</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>کد رهگیری</th>
                    <th>درگاه پرداخت</th>
                    <th>توضیحات</th>
                </tr>
                </thead>
                <tbody class="text-right">
                <?php foreach($model->transactions(array('order'=>'id DESC')) as $transaction):?>
                <tr>
                    <td><?php echo JalaliDate::date('d F Y - H:i', $transaction->date);?></td>
                    <td><?php echo number_format($transaction->amount, 0).' تومان';?></td>
                    <td><span class="label label-<?php
                        echo $transaction->status == 'paid'? 'success':'danger';
                        ?>"><?php echo $transaction->getStatusLabel();?></span></td>
                    <td><b><?php echo CHtml::encode($transaction->token);?></b></td>
                    <td><?= $transaction->gatewayLabels[$transaction->gateway_name]?></td>
                    <td><?php echo CHtml::encode($transaction->description);?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php endif;?>
</div>
