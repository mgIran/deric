<script language="javascript" type="text/javascript">
	function postRefId (refIdValue) {
		var form = document.createElement("form");
		form.setAttribute("method", "POST");
		form.setAttribute("action", "https://bpm.shaparak.ir/pgwchannel/startpay.mellat");
		form.setAttribute("target", "_self");
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("name", "RefId");
		hiddenField.setAttribute("value", refIdValue);
		form.appendChild(hiddenField);

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}
	postRefId('<?php echo $ReferenceId; ?>');
</script>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-info">
			<button data-dismiss="alert" class="close" type="button">
				<i class="icon-remove"></i>
			</button>
			<strong><?php echo Yii::t('rezvan', 'Transfer to the Portal Bank'); ?></strong>

			<?php echo Yii::t('rezvan', 'You will be transferred to the bank site...'); ?>
			<br>
		</div>
	</div>
</div>
