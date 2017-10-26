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
                    <th>توضیحات</th>
                    <th>کد رهگیری</th>
                </tr>
                </thead>
                <tbody class="text-right">
                <?php foreach($model->transactions(array('order'=>'id DESC')) as $transaction):?>
                <tr>
                    <td><?php echo JalaliDate::date('d F Y - H:i', $transaction->date);?></td>
                    <td><?php echo number_format($transaction->amount, 0).' تومان';?></td>
                    <td<?php
                    echo $transaction->status == 'paid'? ' class="text-success"':' class="text-danger"';
                    ?>><?php echo $transaction->getStatusLabel();?></td>
                    <td><?php echo CHtml::encode($transaction->description);?></td>
                    <td><?php echo CHtml::encode($transaction->token);?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php endif;?>
</div>
