<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */

?>

<div class="dashboard-container ticket-box">
	<h3 class="page-name">پشتیبانی</h3>
	<div class="container-fluid tab-content">
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
