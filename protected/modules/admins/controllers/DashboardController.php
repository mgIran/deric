<?php

class DashboardController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        $adminRoles = AdminRoles::model()->findAll(array(
            'select' => 'role'
        ));
        $adminRoles = CHtml::listData($adminRoles, 'role', 'role');
        return array(
            array('allow',  // allow all users to perform 'index' and 'views' actions
                'actions' => array('index'),
                'roles' => $adminRoles,
            ),
            array('deny',  // deny all users
                'actions' => array('index'),
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        Yii::app()->getModule('users');

        $criteria = new CDbCriteria();
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('title!=""');
        $criteria->params = array(':confirm' => 'pending', ':deleted' => '0');
        $newestPrograms = new CActiveDataProvider('Apps', array(
            'criteria' => $criteria,
        ));

        $criteria = new CDbCriteria();
        $criteria->with = 'user';
        $criteria->addCondition('user.role_id=2');
        $criteria->addCondition('user.status=:userStatus');
        $criteria->addCondition('details_status=:status');
        $criteria->params = array(':status' => 'pending', ':userStatus' => 'active');
        $newestDevelopers = new CActiveDataProvider('UserDetails', array(
            'criteria' => $criteria,
        ));

        $criteria = new CDbCriteria();
        $criteria->with = 'user';
        $criteria->addCondition('user.status=:userStatus');
        $criteria->params = array(':userStatus' => 'active');
        $newestDevIdRequests = new CActiveDataProvider('UserDevIdRequests', array(
            'criteria' => $criteria,
        ));

        $criteria = new CDbCriteria();
        $criteria->with = 'app';
        $criteria->alias = 'package';
        $criteria->addCondition('package.status=:packageStatus');
        $criteria->addCondition('package.for=:for');
        $criteria->addCondition('app.title!=""');
        $criteria->params = array(
            ':packageStatus' => 'pending',
            ':for' => 'new_app',
        );
        $newestPackages = new CActiveDataProvider('AppPackages', array(
            'criteria' => $criteria,
        ));

        $criteria = new CDbCriteria();
        $criteria->with = 'app';
        $criteria->alias = 'package';
        $criteria->addCondition('package.status=:packageStatus');
        $criteria->addCondition('package.for=:for');
        $criteria->addCondition('app.title!=""');
        $criteria->params = array(
            ':packageStatus' => 'pending',
            ':for' => 'old_app',
        );
        $updatedPackages = new CActiveDataProvider('AppPackages', array(
            'criteria' => $criteria,
        ));

        Yii::import("tickets.models.*");
        $criteria = new CDbCriteria();
        $criteria->with[] = 'messages';
        $criteria->compare('messages.visit', 0);
        $criteria->compare('messages.sender', 'user');
        $statistics = array(
            'tickets' => Tickets::model()->count($criteria),
            'apps' => Apps::model()->count('title IS NOT NULL'),
            'developers' => Users::model()->count('role_id = 2'),
            'transactions' => UserTransactions::model()->count(),
        );

        // today sales
        $criteria = new CDbCriteria();
        $criteria->addCondition('date > :from_date');
        $criteria->addCondition('date < :to_date');
        $criteria->params[':from_date'] = strtotime(date('Y/m/d 00:00:01'));
        $criteria->params[':to_date'] = strtotime(date('Y/m/d 23:59:59'));
        $report = AppBuys::model()->findAll($criteria);
        /* @var $appBuy AppBuys */
        $labels = $values = array();
        foreach ($report as $appBuy) {
            if(!in_array(CHtml::encode($appBuy->app->title), $labels))
                $labels[] = CHtml::encode($appBuy->app->title);
            $count = 0;
            $key=null;
            foreach ($report as $model) {
                if ($model->app_id == $appBuy->app->id) {
                    $count++;
                    $key=$model->app_id;
                }
            }
            $values[$key] = $count;
        }

        $this->render('index', array(
            'newestPackages' => $newestPackages,
            'updatedPackages' => $updatedPackages,
            'newestPrograms' => $newestPrograms,
            'devIDRequests' => $newestDevIdRequests,
            'newestDevelopers' => $newestDevelopers,
            'statistics' => $statistics,
            'todaySales' => array('labels' => $labels, 'values' => array_values($values)),
        ));
    }
}