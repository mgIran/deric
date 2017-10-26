<?php
/* @var $this UsersManageController */
/* @var $model Users */

Yii::app()->clientScript->registerCss('imgSize','
.national-card-image
{
	max-width:500px;
	max-height:500px;
}
');

$this->breadcrumbs=array(
	'کاربران'=>array('index'),
	$model->userDetails->fa_name && !empty($model->userDetails->fa_name)?$model->userDetails->fa_name:$model->email,
);

$this->menu=array(
	array('label'=>'مدیرت کاربران', 'url'=>array('admin')),
	array('label'=>'تایید اطلاعات کاربر', 'url'=>array('confirmDeveloper', 'id'=>$model->id)),
	array('label'=>'رد اطلاعات کاربر', 'url'=>array('refuseDeveloper', 'id'=>$model->id)),
	array('label'=>'حذف کاربر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف کاربر اطمینان دارید؟')),
);
?>
<style>
	a.btn{
		width: 120px;
	}
</style>
<div class="row">
	<section class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-left">
		<div class="box box-info">
			<div class="box-header with-border"><h3 class="box-title">عملیات</h3></div>
			<div class="box-body">
				<p class="text-center"><b>وضعیت فعلی کاربر: <?= $model->statusLabels[$model->status] ?></b></p>
				<?
				$statuses = $model->statusLabels;
				unset($statuses[$model->status]);
				foreach($statuses as $key => $label):
					?>
					<div class="form-group text-center">
						<a class="btn btn-<?php
						if($key == 'pending') echo 'info';
						if($key == 'active') echo 'success';
						if($key == 'blocked') echo 'warning';
						if($key == 'deleted') echo 'danger';
						?>" href="<?= $this->createUrl("/users/manage/changeStatus/{$model->id}?status={$key}") ?>" ><?= $label ?></a>
					</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
	</section>
	<section class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pull-right">
		<div class="box box-primary">
			<div class="box-header with-border"><h3 class="box-title">نمایش اطلاعات <?php echo $model->userDetails->fa_name && !empty($model->userDetails->fa_name)?$model->userDetails->fa_name:$model->email; ?></h3></div>
			<div class="box-body">
				<?php if($model->userDetails->type == 'real'):?>
					<?php $this->widget('zii.widgets.CDetailView', array(
						'data'=>$model,
						'attributes'=>array(
							array(
								'name'=>'نام',
								'value'=>$model->userDetails->fa_name,
							),
							array(
								'name'=>'نام انگلیسی',
								'value'=>$model->userDetails->en_name,
							),
							array(
								'name'=>'شناسه توسعه دهنده',
								'value'=>$model->userDetails->developer_id,
							),
							array(
								'name'=>'اعتبار',
								'value'=>number_format($model->userDetails->credit,0).'تومان',
							),
							array(
								'name'=>'آدرس وبسایت فارسی',
								'value'=>$model->userDetails->fa_web_url,
							),
							array(
								'name'=>'آدرس وبسایت انگلیسی',
								'value'=>$model->userDetails->en_web_url,
							),
							array(
								'name'=>'شماره تماس',
								'value'=>$model->userDetails->phone,
							),
							array(
								'name'=>'کد ملی',
								'value'=>$model->userDetails->national_code,
							),
							array(
								'name'=>'کد پستی',
								'value'=>$model->userDetails->zip_code,
							),
							array(
								'name'=>'آدرس',
								'value'=>$model->userDetails->address,
							),
							array(
								'name'=>'نوع کاربری',
								'value'=>$model->role->name,
							),
							array(
								'name'=>'امتیاز خرید',
								'value'=>$model->userDetails->score,
							),
							array(
								'name'=>'امتیاز فروش',
								'value'=>$model->userDetails->dev_score,
							),
							array(
								'name'=>'تصویر کارت ملی',
								'value'=>CHtml::image(Yii::app()->baseUrl."/uploads/users/national_cards/".$model->userDetails->national_card_image, '', array('class'=>'national-card-image')),
								'type'=>'raw'
							),
							array(
								'name'=>'وضعیت',
								'value'=>$model->statusLabels[$model->status],
							),
							array(
								'name'=>'وضعیت اطلاعات',
								'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->details_status],
							),
						),
					)); ?>
				<?php else:?>
					<?php $this->widget('zii.widgets.CDetailView', array(
						'data'=>$model,
						'attributes'=>array(
							array(
								'name'=>'نوع عضویت',
								'value'=>$model->userDetails->typeLabels[$model->userDetails->type],
							),
							array(
								'name'=>'نام و نام خانوادگی',
								'value'=>$model->userDetails->fa_name,
							),
							array(
								'name'=>'شناسه توسعه دهنده',
								'value'=>$model->userDetails->developer_id,
							),
							array(
								'name'=>'اعتبار',
								'value'=>number_format($model->userDetails->credit,0).'تومان',
							),
							array(
								'name'=>'آدرس وبسایت',
								'value'=>$model->userDetails->fa_web_url,
							),
							array(
								'name'=>'سمت',
								'value'=>$model->userDetails->postLabels[$model->userDetails->post],
							),
							array(
								'name'=>'نام شرکت',
								'value'=>$model->userDetails->company_name,
							),
							array(
								'name'=>'شماره ثبت',
								'value'=>$model->userDetails->registration_number,
							),
							array(
								'name'=>'شماره تماس',
								'value'=>$model->userDetails->phone,
							),
							array(
								'name'=>'کد پستی',
								'value'=>$model->userDetails->zip_code,
							),
							array(
								'name'=>'آدرس',
								'value'=>$model->userDetails->address,
							),
							array(
								'name'=>'نوع کاربری',
								'value'=>$model->role->name,
							),
							array(
								'name'=>'امتیاز خرید',
								'value'=>$model->userDetails->score,
							),
							array(
								'name'=>'امتیاز فروش',
								'value'=>$model->userDetails->dev_score,
							),
							array(
								'name'=>'تصویر گواهی ثبت',
								'value'=>CHtml::image(Yii::app()->baseUrl."/uploads/users/registration_certificate/".$model->userDetails->registration_certificate_image, '', array('class'=>'national-card-image')),
								'type'=>'raw'
							),
							array(
								'name'=>'وضعیت',
								'value'=>$model->statusLabels[$model->status],
							),
							array(
								'name'=>'وضعیت اطلاعات',
								'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->details_status],
							),
						),
					)); ?>
				<?php endif;?>
			</div>
		</div>
	</section>
</div>