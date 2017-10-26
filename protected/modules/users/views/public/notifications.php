<?php
/* @var $this PublicController */
/* @var $model UserNotifications */
?>
<div class="dashboard-container">
    <h3 class="page-name">اطلاعیه ها</h3>
    <div class="tab-content card-container">
        <ul>
            <?php foreach($model as $notification):?>
                <li style="margin: 15px 0;font-weight: <?php echo ($notification->seen==0)?'bold;color:#d9534f;':'normal';?>"><?php echo CHtml::encode($notification->message);?> | <small><?php echo JalaliDate::date('d F Y - H:i', $notification->date);?></small></li>
            <?php endforeach;?>
        </ul>
    </div>
</div>