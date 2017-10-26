
<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */

?>
<style>
	a.btn{
		width: 120px;
	}
</style>
<?
if(Yii::app()->user->type == 'admin'):
	if($model->status == 'close'):
		$this->renderPartial('//layouts/_alertMessage',array(
			'type' => 'danger',
			'message' => 'تیکت مورد نظر بسته شده، جهت ارسال پیام تیکت را باز کنید.'
		));
	elseif($model->status == 'pending'):
		$this->renderPartial('//layouts/_alertMessage',array(
			'type' => 'warning',
			'message' => 'پیام در حال بررسی توسط کارشناس می باشد.'
		));
	endif;
else:
	if($model->status == 'close'):
		$this->renderPartial('//layouts/_alertMessage',array(
			'type' => 'danger',
			'message' => 'تیکت مورد نظر بسته شده و امکان ارسال پیام وجود ندارد.'
		));
	elseif($model->status == 'pending'):
		$this->renderPartial('//layouts/_alertMessage',array(
			'type' => 'warning',
			'message' => 'پیام شما در حال بررسی توسط کارشناس می باشد.'
		));
	endif;
endif;
?>
<div class="row">
	<section class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-left">
		<div class="box box-info">
			<div class="box-header with-border"><h3 class="box-title">عملیات</h3></div>
			<div class="box-body">
				<?
				if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin'):
				?>
					<div class="form-group text-center">
						<a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/admin') ?>" >لیست تیکت ها</a>
					</div>
				<?
				endif;
				?>
				<?
				if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
				?>
					<div class="form-group text-center">
						<a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/') ?>" >لیست تیکت ها</a>
					</div>
				<?
				endif;
				?>
				<?
				if($model->status != 'close'):
				?>
					<div class="form-group text-center">
						<a class="btn btn-danger" href="<?= $this->createUrl('/tickets/manage/closeTicket/'.$model->code) ?>" >بستن تیکت</a>
					</div>
				<?
				endif;
				?>
				<?
				if(!Yii::app()->user->isGuest && Yii::app()->user->type != 'user'):
					if($model->status != 'pending'):
						?>
						<div class="form-group text-center">
							<a class="btn btn-warning" href="<?= $this->createUrl('/tickets/manage/pendingTicket/'.$model->code) ?>" >در حال بررسی</a>
						</div>
						<?
					endif;
					if($model->status == 'pending' || $model->status == 'close' || $model->status == 'waiting'):
						?>
						<div class="form-group text-center">
							<a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/openTicket/'.$model->code) ?>" >باز</a>
						</div>
						<?
					endif;
				endif;
				?>
			</div>
		</div>
	</section>
	<section class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pull-right">
		<div class="box box-primary">
			<div class="box-header with-border"><h3 class="box-title">تیکت شماره #<?php echo $model->code; ?></h3>&nbsp;<small>(وضعیت تیکت: <?= $model->statusLabels[$model->status] ?>)</small></div>
			<div class="box-body">
				<div class="ticket-detail">
					<h5>موضوع: <?= $model->subject ?></h5>
					<span class="ticket-date">تاریخ ایجاد: <?= Controller::parseNumbers(JalaliDate::date("Y/m/d H:i:s" ,$model->date)) ?></span>
				</div>
				<? $this->renderPartial('//layouts/_flashMessage') ?>
				<?php
				if($model->status != 'close')
					$this->renderPartial('tickets.views.messages._form',array(
						'model' => new TicketMessages(),
						'ticket' => $model
					))
				?>
			</div>
		</div>

		<div class="box box-warning">
			<div class="box-header with-border"><h3 class="box-title">پیام ها</h3></div>
			<div class="box-body">
				<?php
				$this->widget('zii.widgets.CListView', array(
					'id' => 'message-list',
					'dataProvider' => new CArrayDataProvider($model->messages,array('pagination' => false)),
					'itemView' => '_messageView',
					'template' => '{items}'
				));
				?>
			</div>
		</div>
	</section>
</div>