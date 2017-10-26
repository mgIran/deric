<?php
/* @var $this AppsController */
/* @var $model Apps */
/* @var $user Users */
/* @var $bought boolean */
/* @var $price string */
?>

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buy-box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">خرید</h3>
        </div>
        <div class="panel-body step-content">
            <div class="container-fluid buy-form">
                <?php $this->renderPartial('//layouts/_flashMessage');?>
                <?php if(Yii::app()->user->hasFlash('credit-failed')):?>
                <div class="alert alert-danger fade in">
                    <?php echo Yii::app()->user->getFlash('credit-failed');?>
                    <?php if(Yii::app()->user->hasFlash('failReason') and Yii::app()->user->getFlash('failReason')=='min_credit'):?>
                        <a href="<?php echo $this->createUrl('/users/credit/buy');?>">خرید اعتبار</a>
                    <?php endif;?>
                </div>
                <?php endif;?>
                <?php
                if($user):
                ?>
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'app-buys-form',
                        'enableAjaxValidation'=>false,
                    )); ?>
                        <h4>اطلاعات برنامه</h4>
                        <p><span class="buy-label">برنامه </span><span class="buy-value"><a><?php echo CHtml::encode($model->title);?></a></span></p>
                        <p><span class="buy-label">مبلغ</span><span class="buy-value"><?php echo CHtml::encode(number_format($price, 0));?> تومان</span></p>
                        <h4>اطلاعات کاربری</h4>
                        <p><span class="buy-label">اعتبار فعلی</span><span class="buy-value"><?php echo CHtml::encode(number_format($user->userDetails->credit, 0));?> تومان</span></p>
                        <?php if($bought):?>
                            <div class="alert alert-success fade in">
                                مبلغ این برنامه قبلا از حساب شما کسر گردیده است. شما می توانید از <a href="<?php echo $this->createUrl('/apps/download/'.CHtml::encode($model->id).'/'.CHtml::encode($model->title));?>">اینجا</a> این برنامه را دانلود کنید.
                            </div>
                        <?php else:?>
                            <?php if(Yii::app()->user->getId()!=$model->developer_id):?>
                                <?php echo CHtml::submitButton('پرداخت آنلاین', array(
                                    'class'=>'btn btn-info btn-buy pull-left',
                                    'name'=>'Buy[gateway]',
                                    'style'=>'margin-right:15px;'
                                ))?>
                                <?php echo CHtml::submitButton('کسر از اعتبار', array(
                                    'class'=>'btn btn-success btn-buy pull-left',
                                    'name'=>'Buy[credit]'
                                ))?>
                            <?php endif;?>
                        <?php endif;?>
                    <?php $this->endWidget();?>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
