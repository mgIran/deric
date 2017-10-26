<?php
/* @var $this PanelController*/
/* @var $settlementHistory CActiveDataProvider*/
/* @var $settlementRequiredUsers CActiveDataProvider*/
?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">تاریخچه تسویه حساب ها</h3>
    </div>
    <div class="box-body">
        <?php $this->renderPartial('//layouts/_flashMessage');?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'settlements-grid',
                'dataProvider'=>$settlementHistory,
                'columns'=>array(
                    'date'=>array(
                        'name'=>'date',
                        'value'=>'JalaliDate::date("d F Y", $data->date)'
                    ),
                    'amount'=>array(
                        'name'=>'amount',
                        'value'=>'number_format($data->amount, 0)." تومان"'
                    ),
                ),
            ));?>
        </div>
    </div>
</div>


<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">کاربرانی که درخواست تسویه حساب دارند</h3>
    </div>
    <div class="box-body">
        <?php
        echo CHtml::form('excel');
        ?>
            <?php echo CHtml::submitButton('دریافت فایل اکسل کامل', array(
                'class'=>'btn btn-success',
                'name'=>'show-chart',
                'id'=>'show-chart',
            ));?>
        <?php
        echo CHtml::endForm();
        ?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'required-settlements-grid',
                'dataProvider'=>$settlementRequiredUsers,
                'columns'=>array(
                    'fa_name'=>array(
                        'name'=>'fa_name',
                        'value'=>'CHtml::link($data->user->userDetails->fa_name, Yii::app()->createUrl("/users/manage/views/".$data->user->id))',
                        'type'=>'raw'
                    ),
                    'iban'=>array(
                        'name'=>'iban',
                        'value'=>'"IR".$data->iban'
                    ),
                    'amount'=>array(
                        'header'=>'مبلغ قابل تسویه',
                        'value'=>'number_format($data->getSettlementAmount(), 0)." تومان"'
                    ),
                    'settled'=>array(
                        'value'=>function($data){
                            $form=CHtml::beginForm(Yii::app()->createUrl("/developers/panel/manageSettlement"), 'post', array('class'=>'settlement-form'));
                            $form.=CHtml::textField('iban', '', array('class'=>'token','placeholder'=>'شماره شبا *'));
                            $form.=CHtml::textField('token', '', array('class'=>'token','placeholder'=>'کد رهگیری *'));
                            $form.=CHtml::textField('amount', '', array('placeholder'=>'مبلغ تسویه(تومان) *'));
                            $form.=CHtml::hiddenField('user_id', $data->user_id);
                            $form.=CHtml::submitButton('تسویه شد', array('class'=>'btn btn-success btn-sm'));
                            $form.=CHtml::endForm();
                            return $form;
                        },
                        'type'=>'raw'
                    ),
                ),
            ));?>
        </div>
    </div>
</div>