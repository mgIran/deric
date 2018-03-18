<?php
/* @var $this PanelController */
/* @var $detailsModel UserDetails */
/* @var $devIdRequestModel UserDevIdRequests */
/* @var $nationalCardImage array */
/* @var $registrationCertificateImage array */
?>
<div class="dashbord container-fluid">
    <div class="card-container">
        <div class="dashbord-header">
            <span class="glyphicon left-icon"></span>
            <h3><strong>حساب توسعه دهنده</strong></h3>
        </div>
        <div class="dashbord-body">
            <?php $this->renderPartial('//layouts/_flashMessage'); ?>
            <?php $this->renderPartial('_update_profile_form', array(
                'model'=>$detailsModel,
                'nationalCardImage'=>$nationalCardImage,
                'registrationCertificateImage'=>$registrationCertificateImage,
            ));?>

            <?php if(empty($detailsModel->developer_id)):?>
                <?php $this->renderPartial('_change_developer_id_form', array(
                    'model'=>$devIdRequestModel,
                ));?>
            <?php else:?>
                <div class="col-md-6">
                    <h4>شناسه توسعه دهنده</h4>
                    <?php echo CHtml::label('شناسه شما: ', '');?>
                    <?php echo $detailsModel->developer_id;?>
                    <p class="desc">این شناسه دیگر قابل تغییر نیست.</p>
                    <h4 style="margin-top: 70px;">امتیاز</h4>
                    <?php echo CHtml::label('امتیاز شما:', '');?>
                    <?php echo $detailsModel->dev_score;?>
                    <p class="desc">به ازای فروش هر برنامه یک امتیاز در نظر گرفته می شود.</p>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>