<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&views=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Yii::import('rows.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';

        /* last data query
         // get newest programs
        $catIds = AppCategories::model()->getCategoryChilds(1);
        $criteria = Apps::getValidApps($this->platform, $catIds);
        $newestProgramDP = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        // get newest games
        $catIds = AppCategories::model()->getCategoryChilds(2);
        $criteria = Apps::getValidApps($this->platform, $catIds);
        $newestGameDataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

//        // get newest educations
//        $catIds = AppCategories::model()->getCategoryChilds(3);
//        $criteria = Apps::getValidApps($this->platform, $catIds);
//        $newestEducationDataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        // get suggested list
        $visitedCats = CJSON::decode(base64_decode(Yii::app()->request->cookies['VC']));
        $criteria = Apps::getValidApps($this->platform, $visitedCats);
        $suggestedDataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        // get top programs
        $catIds = AppCategories::model()->getCategoryChilds(1);
        $criteria = Apps::getValidApps($this->platform, $catIds);
        $criteria->select = 't.*, AVG(ratings.rate) as avgRate';
        $criteria->addCondition('ratings.rate IS NOT NULL');
        $criteria->with = array('images', 'ratings');
        $criteria->together = true;
        $criteria->order = 'avgRate DESC, t.id DESC';
        $criteria->group = 't.id';
        $topProgramDataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        // get bestselling programs
        $catIds = AppCategories::model()->getCategoryChilds(1);
        $criteria = Apps::getValidApps($this->platform, $catIds);
        $criteria->with = array('images', 'appBuys' => array('joinType' => 'RIGHT OUTER JOIN'));
        $criteria->together = true;
        $criteria->order = 'COUNT(appBuys.id) DESC';
        $criteria->group = 'appBuys.app_id';
        $bestsellingProgramDataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));
         */

        // const rows
        $dynamicRows = RowsHomepage::model()->findAll(RowsHomepage::getActiveRows());

        // get special advertise
        Yii::import('advertises.models.*');
        $commonAdvertises  = AppAdvertises::model()->findAll(AppAdvertises::CommonQuery($this->platform));
        $specialAdvertises = AppAdvertises::model()->findAll(AppAdvertises::SpecialQuery($this->platform));

        $this->render('index', compact(
//            'newestProgramDataProvider',
//            'newestGameDataProvider',
////            'newestEducationDataProvider' => $newestEducationDataProvider,
//            'suggestedDataProvider',
//            'specialAdvertise',
//            'topProgramDataProvider',
//            'bestsellingProgramDataProvider',
            'commonAdvertises',
            'specialAdvertises',
            'dynamicRows'
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/error';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionAbout()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(1);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionContactUs()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(2);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionHelp()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(3);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionTerms()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(4);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionPrivacy()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(5);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionUnderConstruction()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/empty';
        $this->render('//site/pages/under_construction');
    }

    public function actionMellatRedirect(){
        if(isset($_GET['responseCode']))
            $this->render('ext.MellatPayment.views._redirect', array(
                'ReferenceId' => $_GET['responseCode']
            ));
        else
            throw new CHttpException(404, "Response Code not sent.");
    }

    // temporary actions
    public function actionRunSql(){
        $sql = "";
        $flag = Yii::app()->db->createCommand($sql)
               ->execute();
        var_dump($flag);exit;
    }
}