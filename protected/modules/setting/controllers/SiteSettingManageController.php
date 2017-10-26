<?php

class SiteSettingManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'changeSetting';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('changeSetting', 'gatewaySetting'),
                'roles' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Change site setting
     */
    public function actionChangeSetting()
    {
        if(isset($_POST['SiteSetting'])){
            foreach($_POST['SiteSetting'] as $name => $value){
                if($name == 'buy_credit_options'){
                    $value = explode(',', $value);
                    $field = SiteSetting::model()->findByAttributes(array('name' => $name));
                    SiteSetting::model()->updateByPk($field->id, array('value' => CJSON::encode($value)));
                }else{
                    $field = SiteSetting::model()->findByAttributes(array('name' => $name));
                    SiteSetting::model()->updateByPk($field->id, array('value' => $value));
                }
            }
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            $this->refresh();
        }
        $model = SiteSetting::model()->findAll('name NOT REGEXP \'\\([^\\)]*gateway_.*\\)\'');
        $this->render('_general', array(
            'model' => $model
        ));
    }

    /**
     * Change gateway setting
     */
    public function actionGatewaySetting()
    {
        $gateway_active = SiteSetting::getOption('gateway_active', false);
        $gateway_variables = SiteSetting::getOption('gateway_variables', false);
        if(isset($_POST['SiteSetting'])){
            SiteSetting::setOption('gateway_active', $_POST['SiteSetting']['gateway_active']);
            SiteSetting::setOption('gateway_variables', CJSON::encode($_POST['SiteSetting']['gateway_variables']));
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            $this->refresh();
        }
        $this->render('_gateway', array(
            'gateway_active' => $gateway_active,
            'gateway_variables' => $gateway_variables
        ));
    }
}