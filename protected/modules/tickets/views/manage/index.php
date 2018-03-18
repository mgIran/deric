<?php
/* @var $this TicketsManageController */
/* @var $model Tickets[] */

?>
<div class="dashbord container-fluid">
    <div class="dashbord-header">
        <span class="glyphicon left-icon"></span>
        <h3><strong>پشتیبانی</strong></h3>
        <a class="btn btn-primary pull-left" href="<?= $this->createUrl('/tickets/manage/create') ?>" >تیکت جدید</a>
    </div>
    <div class="dashbord-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>کد تیکت</th>
                    <th>موضوع</th>
                    <th>بخش</th>
                    <th>وضعیت</th>
                    <th>زمان</th>
                </tr>
                </thead>
                <tbody>
                <?php if($model):?>
                    <?php foreach($model as $key => $ticket):?>
                        <tr>
                            <td><?php echo $key+1 ?></td>
                            <td>
                                <a href="<?= $this->createUrl('/tickets/'.$ticket->code) ?>">
                                    <?php echo $ticket->code ?>
                                </a>
                            </td>
                            <td><?php echo CHtml::encode($ticket->subject);?></td>
                            <td><?php echo CHtml::encode($ticket->department->title);?></td>
                            <td><?php echo $ticket->status?CHtml::encode($ticket->statusLabels[$ticket->status]):'-';?></td>
                            <td><?php echo JalaliDate::date('d F Y H:i', $ticket->date);?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
                <tfoot>
                <?php if(!$model):?>
                    <tr>
                        <td colspan="6">نتیجه ای یافت نشد.</td>
                    </tr>
                <?php endif;?>
                </tfoot>
            </table>
        </div>
    </div>
</div>