<?php
/* @var $this BaseManageController */
/* @var $model Apps */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'apps-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )
)); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, 'class'=> 'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'developer_team'); ?>
		<?php echo $form->textField($model,'developer_team',array('size'=>50,'maxlength'=>500, 'class'=> 'form-control')); ?>
		<?php echo $form->error($model,'developer_team'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',AppCategories::model()->sortList(), array('class'=> 'form-control')); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',array(
				'enable' => 'فعال',
				'disable' => 'غیر فعال'
		),array('class'=> 'form-control'));
        ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group">
		<?php if($model->price == 0) {
			$r = 'free';
			$p = null;
		}
		else if($model->price == -1)
		{
			$r = 'in-app-payment';
			$p = null;
		}else
		{
			$r = 'online-payment';
			$p = $model->price;
		}
		echo CHtml::radioButtonList('priceType',$r,array('online-payment'=>'پرداخت آنلاین','in-app-payment'=>'پرداخت درون برنامه ای','free'=>'رایگان'),
			array(
				'class'=>'priceType',
			)
		);
		Yii::app()->clientScript->registerScript('priceType', '
			$("body").on("change",".priceType",function(){
				var priceType = $(this).val();
				var priceInput = $("#price-input");
				$(\'.portion\').addClass(\'hidden\');
				$(\'#tax-tag\').text("");
				$(\'#market-portion\').text("");
				$(\'#developer-portion\').text("");
				switch(priceType){
					case \'free\':
						priceInput.val("").attr("disabled",true).attr("readonly",true);
						break;
					case \'in-app-payment\':
						priceInput.val("").attr("disabled",true).attr("readonly",true);
						break;
					case \'online-payment\':
						priceInput.val("").attr("disabled",false).attr("readonly",false);
						break;
				}
			});
		',CClientScript::POS_READY);
		?>
		<?php echo CHtml::textField('Apps[price]',$p,array(
			'placeholder'=>$model->getAttributeLabel('price').' (تومان) *',
			'class'=>'form-control price',
			'id'=>'price-input',
			'disabled' => $model->price>0?false:true,
			'readonly' => $model->price>0?false:true
		)); ?>
		<?php echo $form->error($model,'price'); ?>
		<?php echo CHtml::hiddenField('tax', $tax);?>
		<?php echo CHtml::hiddenField('commission', $commission);?>
		<?php Yii::app()->clientScript->registerScript('portion', "
			$('#price-input').on('keydown', function(e){
				if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8)
					return true;
				else
					return false;
			});
			if($('#price-input').val()!='') {
				$('.portion').removeClass('hidden');
				var price=$(this).val();
				$('#tax-tag').text((price*parseInt($('#tax').val()))/100);
				$('#market-portion').text((price*parseInt($('#commission').val()))/100);
				$('#developer-portion').text(price-parseInt($('#tax-tag').text())-parseInt($('#market-portion').text()));
			}
			else
				$('.portion').addClass('hidden');
			$('#price-input').on('keyup', function(e){
				if($(this).val()!='') {
					$('.portion').removeClass('hidden');
					var price=$(this).val();
					$('#tax-tag').text((price*parseInt($('#tax').val()))/100);
					$('#market-portion').text((price*parseInt($('#commission').val()))/100);
					$('#developer-portion').text(price-parseInt($('#tax-tag').text())-parseInt($('#market-portion').text()));
				}
				else
					$('.portion').addClass('hidden');
			});
			$('.priceType[value=\"free\"]').on('mousedown', function(){
				var res = confirm('پس از رایگان کردن برنامه امکان بازگرداندن به حالت غیر رایگان وجود ندارد. \\nآیا مطمئن هستید که میخواهید برنامه را رایگان کنید؟');
				if(res == true)
					$(this).trigger('click');
				else
					return false;
			});
			$('.priceType[value=\"free\"] + label').on('mousedown', function(){
				var res = confirm('پس از رایگان کردن برنامه امکان بازگرداندن به حالت غیر رایگان وجود ندارد. \\nآیا مطمئن هستید که میخواهید برنامه را رایگان کنید؟');
				if(res == true)
					$('.priceType[value=\"free\"]').trigger('click');
				else
					return false;
			});
		");?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'icon'); ?>
        <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
            'id' => 'uploaderIcon',
            'model' => $model,
            'name' => 'icon',
            'maxFiles' => 1,
            'maxFileSize' => 0.3, //MB
            'url' => Yii::app()->createUrl('/manageApps/'.$this->controller.'/upload'),
            'deleteUrl' => Yii::app()->createUrl('/manageApps/'.$this->controller.'/deleteUpload'),
            'acceptedFiles' => 'image/png',
            'serverFiles' => $icon,
            'onSuccess' => '
                var responseObj = JSON.parse(res);
                if(responseObj.state == "ok")
                {
                    {serverName} = responseObj.fileName;
                }else if(responseObj.state == "error"){
                    alert(responseObj.msg);
                    this.removeFile(file);
                }
            ',
        ));
        ?>
		<?php echo $form->error($model,'icon'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php
		$this->widget('ext.ckeditor.CKEditor',array(
			'model' => $model,
			'attribute' => 'description'
		));
		?>
		<?php echo $form->error($model,'description'); ?>
	</div>

<!--	<div class="form-group">-->
<!--		--><?php //echo $form->labelEx($model,'change_log'); ?>
<!--		--><?php
//		$this->widget('ext.ckeditor.CKEditor',array(
//				'model' => $model,
//				'attribute' => 'change_log'
//		));
//		?>
<!--		--><?php //echo $form->error($model,'change_log'); ?>
<!--	</div>-->

	<?php if($this->platform_id != 1):?>
		<div class="form-group multipliable-input-container">
			<?php echo CHtml::label('لیست دسترسی های برنامه',''); ?>
			<?php if($model->isNewRecord):?>
				<?php echo CHtml::textField('Apps[permissions][0]','',array('placeholder'=>'دسترسی','class'=>'form-control multipliable-input')); ?>
			<?php else:
				if($model->permissions) {
					?>
					<?php
					foreach (CJSON::decode($model->permissions) as $key => $permission):
						?>
						<?php echo CHtml::textField('Apps[permissions][' . $key . ']', $permission, array('placeholder' => 'دسترسی', 'class' => 'form-control multipliable-input')); ?>
					<?php
					endforeach;
					?>
					<?php
				}else
				{
					echo CHtml::textField('Apps[permissions][0]','',array('placeholder'=>'دسترسی','class'=>'form-control multipliable-input'));
				}
			endif;?>
			<a href="#add-permission" class="add-multipliable-input"><i class="icon icon-plus"></i></a>
			<a href="#remove-permission" class="remove-multipliable-input"><i class="icon icon-trash"></i></a>
			<?php echo $form->error($model,'permissions'); ?>
		</div>
	<?php endif;?>

	<h5 style="margin-top: 50px;">اطلاعات پشتیبانی کاربر</h5>
	<hr>

	<div class="form-group">
		<?php echo $form->textField($model,'support_phone',array('placeholder'=>$model->getAttributeLabel('support_phone'),'maxlength'=>11,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'support_phone'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->textField($model,'support_email',array('placeholder'=>$model->getAttributeLabel('support_email').' *','maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'support_email'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->textField($model,'support_fa_web',array('placeholder'=>$model->getAttributeLabel('support_fa_web'),'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'support_fa_web'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->textField($model,'support_en_web',array('placeholder'=>$model->getAttributeLabel('support_en_web'),'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'support_en_web'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ثبت و ادامه' ,array('class' => 'btn btn-success')); ?>
	</div>

<?php  $this->endWidget(); ?>