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
            <div class="table text-center">
                <div class="thead">
                    <div class="td col-lg-1 col-md-1 col-sm-1 col-xs-2 text-center">#</div>
                    <div class="td col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">کد تیکت</div>
                    <div class="td col-lg-3 col-md-3 col-sm-3 col-xs-4">موضوع</div>
                    <div class="td col-lg-2 col-md-2 col-sm-2 col-xs-3">بخش</div>
                    <div class="td col-lg-2 col-md-2 col-sm-2 col-xs-3">وضعیت</div>
                    <div class="td col-lg-2 col-md-2 col-sm-2 hidden-xs text-center">زمان</div>
                </div>
                <div class="tbody">
                    <?php if(!$model):?>
                        <div class="tr">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">نتیجه ای یافت نشد.</div>
                        </div>
                    <?php else:?>
                        <?php foreach($model as $key => $ticket):?>
                            <div class="tr">
                                <a href="<?= $this->createUrl('/tickets/'.$ticket->code) ?>">
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2 text-center"><?php echo $key+1 ?></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center"><?php echo $ticket->code ?></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4"><?php echo CHtml::encode($ticket->subject);?></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"><?php echo CHtml::encode($ticket->department->title);?></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"><?php echo $ticket->status?CHtml::encode($ticket->statusLabels[$ticket->status]):'-';?></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs text-center"><?php echo JalaliDate::date('d F Y H:i', $ticket->date);?></div>
                                </a>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>