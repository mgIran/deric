<?php
/* @var $data UserSettlement */
?>

<div class="tr">
    <div class="col-md-4"><?php echo CHtml::encode(number_format($data->amount, 0));?> تومان</div>
    <div class="col-md-4"><?php echo JalaliDate::date('d F Y - H:i', $data->date);?></div>
    <div class="col-md-4">IR<?php echo CHtml::encode($data->iban);?></div>
</div>