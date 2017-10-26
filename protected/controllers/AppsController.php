<?php

class AppsController extends Controller
{
    public $layout = '//layouts/inner';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + bookmark',
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
            array('allow',
                'actions' => array('reportSales', 'reportIncome'),
                'roles' => array('admin'),
            ),
            array('allow',
                'actions' => array('discount', 'search', 'view', 'download', 'programs', 'games', 'educations', 'developer', 'top', 'bestselling'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('buy', 'bill', 'bookmark', 'rate', 'comments', 'verify'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id)
    {
        Yii::import('users.models.*');
        Yii::app()->theme = "market";
        $model = $this->loadModel($id);
        if((Yii::app()->user->isGuest || (Yii::app()->user->roles !='admin' && Yii::app()->user->roles !='validator')) && ($model->confirm != 'accepted' || !$model->lastPackage))
            throw new CHttpException(404, 'برنامه موردنظر موجود نیست.');
        $this->app = $model;
        $model->seen = $model->seen + 1;
        $model->save();
        $this->saveInCookie($model->category_id);
        $this->platform = $model->platform_id;
        // Has bookmarked this apps by user
        $bookmarked = false;
        if (!Yii::app()->user->isGuest) {
            $hasRecord = UserAppBookmark::model()->findByAttributes(array('user_id' => Yii::app()->user->getId(), 'app_id' => $id));
            if ($hasRecord)
                $bookmarked = true;
        }
        // Get similar apps
        $criteria = new CDbCriteria();
        $criteria->addCondition('id!=:id');
        $criteria->addCondition('category_id=:cat_id');
        $criteria->addCondition('platform_id=:platform_id');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->order = 'install DESC, seen DESC';
        $criteria->params[':id'] = $model->id;
        $criteria->params[':cat_id'] = $model->category_id;
        $criteria->params[':platform_id'] = $model->platform_id;
        $criteria->params[':status'] = 'enable';
        $criteria->params[':confirm'] = 'accepted';
        $criteria->params[':deleted'] = 0;
        $criteria->limit = 20;
        $criteria->order = 'id DESC';
        $similar = new CActiveDataProvider('Apps', array('criteria' => $criteria));
        $this->render('view', array(
            'model' => $model,
            'similar' => $similar,
            'bookmarked' => $bookmarked,
        ));
    }

    public function actionComments($id)
    {
        Yii::app()->theme = "market";
        $model = $this->loadModel($id);
        $this->layout='panel';

        $this->render('comments', array(
            'model'=>$model,
        ));
    }

    /**
     * Buy app
     */
    public function actionBuy($id)
    {
        Yii::app()->theme = 'market';
        $this->layout = 'panel';
        $userID = Yii::app()->user->getId();
        $model = $this->loadModel($id);
        $price = $model->hasDiscount()?$model->offPrice:$model->price;
        $buy = false;
        $user = false;
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin')
            Yii::app()->user->setFlash('failed', 'لطفا جهت خرید نرم افزار ابتدا وارد حساب کاربری خود شوید.');
        else{
            $buy = AppBuys::model()->findByAttributes(array('user_id' => $userID, 'app_id' => $id));
            if($buy)
                $this->redirect(array('/apps/download/' . CHtml::encode($model->id) . '/' . CHtml::encode($model->title)));
            Yii::app()->getModule('users');
            $user = Users::model()->findByPk(Yii::app()->user->getId());
            /* @var $user Users */

            if($model->developer_id != $userID){
                if(isset($_POST['Buy'])){
                    if(isset($_POST['Buy']['credit'])){
                        if($user->userDetails->credit < $price){
                            Yii::app()->user->setFlash('credit-failed', 'اعتبار فعلی شما کافی نیست!');
                            Yii::app()->user->setFlash('failReason', 'min_credit');
                            $this->refresh();
                        }

                        $userDetails = UserDetails::model()->findByAttributes(array('user_id' => $userID));
                        $userDetails->setScenario('update-credit');
                        $userDetails->credit = $userDetails->credit - $price;
                        $userDetails->score = $userDetails->score + 1;

                        if($userDetails->save()){
                            $buy = $this->saveBuyInfo($model, $price, $user, 'credit');
                            Yii::app()->user->setFlash('success', 'خرید شما با موفقیت انجام شد.');
                            $this->redirect(array('/apps/bill/' . $buy->id));
                        }else
                            Yii::app()->user->setFlash('failed', 'در انجام عملیات خرید خطایی رخ داده است. لطفا مجددا تلاش کنید.');
                    }elseif(isset($_POST['Buy']['gateway'])){
                        // Save payment
                        $transaction = new UserTransactions();
                        $transaction->user_id = Yii::app()->user->getId();
                        $transaction->amount = $price;
                        $transaction->date = time();
                        $transaction->gateway_name = $this->active_gateway;
                        if($transaction->save()){
                            $CallbackURL = Yii::app()->getBaseUrl(true) . '/apps/verify/' . $id;
                            if($this->active_gateway == 'mellat'){
                                $result = Yii::app()->mellat->PayRequest($price * 10, $transaction->id, $CallbackURL);
                                if(!$result['error']){
                                    $ref_id = $result['responseCode'];
                                    $transaction->authority = $ref_id;
                                    $transaction->save(false);
                                    $this->render('ext.MellatPayment.views._redirect', array('ReferenceId' => $result['responseCode']));
                                }else
                                    Yii::app()->user->setFlash('failed', Yii::app()->mellat->getResponseText($result['responseCode']));
                            }else if($this->active_gateway == 'zarinpal'){
                                $siteName = Yii::app()->name;
                                $description = "پرداخت وجه جهت خرید نرم افزار {$model->title} در وبسایت {$siteName}";
                                $result = Yii::app()->zarinpal->PayRequest(doubleval($price), $description, $CallbackURL);
                                $transaction->authority = Yii::app()->zarinpal->getAuthority();
                                $transaction->save(false);
                                if($result->getStatus() == 100)
                                    $this->redirect(Yii::app()->zarinpal->getRedirectUrl());
                                else
                                    Yii::app()->user->setFlash('failed', Yii::app()->zarinpal->getError());
                            }
                        }
                    }
                }
            }else
                Yii::app()->user->setFlash('failed', 'شما توسعه دهنده این برنامه هستید.');
        }
        $this->render('buy', array(
            'model' => $model,
            'price' => $price,
            'user' => $user,
            'bought' => ($buy)?true:false,
        ));
    }

    public function actionVerify($id)
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/panel';
        $app = Apps::model()->findByPk($id);
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        /* @var $model UserTransactions */
        /* @var $app Apps */
        /* @var $user Users */
        $transactionResult = false;
        $result = null;

        if($this->active_gateway == 'mellat'){
            $model = UserTransactions::model()->findByAttributes(array(
                'user_id' => Yii::app()->user->getId(),
                'status' => 'unpaid'));
            if($_POST['ResCode'] == 0)
                $result = Yii::app()->mellat->VerifyRequest($model->id, $_POST['SaleOrderId'], $_POST['SaleReferenceId']);

            if($result != null){
                $RecourceCode = (!is_array($result)?$result:$result['responseCode']);
                if($RecourceCode == 0){
                    // Settle Payment
                    $settle = Yii::app()->mellat->SettleRequest($model->id, $_POST['SaleOrderId'], $_POST['SaleReferenceId']);
                    if($settle){
                        $model->scenario = 'update';
                        $model->status = 'paid';
                        $model->token = $_POST['SaleReferenceId'];
                        $model->save();
                        $transactionResult = true;
                        $buy = $this->saveBuyInfo($app, $model->amount, $user, 'gateway', $model->id);
                        Yii::app()->user->setFlash('success', 'پرداخت شما با موفقیت انجام شد.');
                        $this->redirect(array('/apps/bill/' . $buy->id));
                    }
                }else{
                    Yii::app()->user->setFlash('failed', Yii::app()->mellat->getError($RecourceCode));
                    $this->redirect(array('/apps/buy/' . $id));
                }
            }else
                Yii::app()->user->setFlash('failed', 'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
        }else if($this->active_gateway == 'zarinpal'){
            if(!isset($_GET['Authority'])){
                Yii::app()->user->setFlash('failed', 'Gateway Error: Authority Code not sent.');
                $this->redirect(array('/apps/buy/' . $id));
            }else{
                $Authority = $_GET['Authority'];
                $model = UserTransactions::model()->findByAttributes(array(
                    'authority' => $Authority
                ));
                if($model->status == 'unpaid'){
                    $Amount = $model->amount;
                    if($_GET['Status'] == 'OK'){
                        Yii::app()->zarinpal->verify($Authority, $Amount);
                        if(Yii::app()->zarinpal->getStatus() == 100){
                            $model->scenario = 'update';
                            $model->status = 'paid';
                            $model->token = Yii::app()->zarinpal->getRefId();
                            @$model->save(false);
                            $transactionResult = true;
                            $buy = $this->saveBuyInfo($app, $model->amount, $user, 'gateway', $model->id);
                            Yii::app()->user->setFlash('success', 'پرداخت شما با موفقیت انجام شد.');
                            $this->redirect(array('/apps/bill/' . $buy->id));
                        }else{
                            Yii::app()->user->setFlash('failed', Yii::app()->zarinpal->getError());
                            $this->redirect(array('/apps/buy/' . $id));
                        }
                    }else{
                        Yii::app()->user->setFlash('failed', 'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
                        $this->redirect(array('/apps/buy/' . $id));
                    }
                }
            }
        }


        $this->render('verify', array(
            'transaction' => $model,
            'app' => $app,
            'user' => $user,
            'price' => $model->amount,
            'transactionResult' => $transactionResult,
        ));
    }

    /**
     * Save buy information
     *
     * @param $app Apps
     * @param $price string
     * @param $user Users
     * @param $method string
     * @param $transactionID string
     * @return AppBuys
     */
    private function saveBuyInfo($app, $price, $user, $method, $transactionID = null)
    {
        $appTitle=$app->title;
        $app->download += 1;
        $app->setScenario('update-download');
        $app->save();
        $buy = new AppBuys();
        $buy->app_id = $app->id;
        $buy->user_id = $user->id;
        if ($app->developer) {
            $app->developer->userDetails->earning = $app->developer->userDetails->earning + $app->getDeveloperPortion($price);
            $app->developer->userDetails->dev_score = $app->developer->userDetails->dev_score + 1;
            $app->developer->userDetails->save();
        }
        $buy->save();
        
        /* @var $transaction UserTransactions */
        $transaction = null;
        if (!is_null($transactionID))
            $transaction = UserTransactions::model()->findByPk($transactionID);
        
        $message =
            '<p style="text-align: right;">'.(is_null($user->userDetails->fa_name)?'کاربر':$user->userDetails->fa_name).' عزیز، سلام<br>از اینکه از '.Yii::app()->name.' خرید کردید متشکریم. رسید خریدتان در زیر این صفحه آمده است.</p>
            <p style="text-align: right;">برنامه برای دریافت روی دستگاهتان آماده است. چنانچه در دریافت برنامه به مشکلی برخورد کردید، لطفا ابتدا چک کنید که روی دستگاهتان وارد حساب کاربریتان شده باشید.در صورتی که مشکل از این نبود لطفا با ما تماس بگیرید: hyperapps.ir@gmail.com</p>
            <div style="width: 100%;height: 1px;background: #ccc;margin-bottom: 15px;"></div>
            <h4 style="text-align: right;">صورت حساب</h4>
            <table style="font-size: 9pt;text-align: right;">
                <tr>
                    <td style="font-weight: bold;width: 120px;">تاریخ رسید</td>
                    <td>' . JalaliDate::date('d F Y', $buy->date) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">زمان</td>
                    <td>' . JalaliDate::date('H:i', $buy->date) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">به نام</td>
                    <td>' . $user->email . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">نام برنامه</td>
                    <td>' . CHtml::encode($appTitle.' ('.$app->lastPackage->package_name.')') . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">قیمت (با احتساب مالیات و عوارض)</td>
                    <td>' . Controller::parseNumbers(number_format($price, 0)) . ' تومان</td>
                </tr>';
        if ($method == 'gateway')
            $message .= '<tr>
                    <td style="font-weight: bold;width: 120px;">کد رهگیری</td>
                    <td style="font-weight: bold;letter-spacing:4px">' . CHtml::encode($transaction->token) . ' </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">روش پرداخت</td>
                    <td style="font-weight: bold;">درگاه بانک ملت </td>
                </tr>';
        elseif ($method == 'credit')
            $message .= '<tr>
                    <td style="font-weight: bold;width: 120px;">روش پرداخت</td>
                    <td>کسر از اعتبار</td>
                </tr>';
        $message .= '</table>';
        Mailer::mail($user->email, 'اطلاعات خرید برنامه', $message, Yii::app()->params['noReplyEmail'], Yii::app()->params['SMTP']);
        return $buy;
    }

    public function actionBill($id)
    {
        Yii::app()->theme='market';
        $this->layout='panel';
        $buy=AppBuys::model()->findByPk($id);

        $this->render('bill', array(
            'buy'=>$buy,
        ));
    }

    /**
     * Download app
     */
    public function actionDownload($id, $title)
    {
        $model = $this->loadModel($id);
        $platformFolder = '';
        switch (pathinfo($model->lastPackage->file_name, PATHINFO_EXTENSION)) {
            case 'apk':
                $platformFolder = 'android';
                break;

            case 'ipa':
                $platformFolder = 'ios';
                break;

            case 'xap':
                $platformFolder = 'windowsphone';
                break;
        }
        if ($model->price == 0) {
            $model->install += 1;
            $model->setScenario('update-install');
            $model->save();
            $this->download($model->lastPackage->file_name, Yii::getPathOfAlias("webroot") . '/uploads/apps/files/' . $platformFolder);
        } else {
            $buy = AppBuys::model()->findByAttributes(array('user_id' => Yii::app()->user->getId(), 'app_id' => $id));
            if ($buy) {
                $model->install += 1;
                $model->setScenario('update-install');
                $model->save();
                $this->download($model->lastPackage->file_name, Yii::getPathOfAlias("webroot") . '/uploads/apps/files/' . $platformFolder);
            } else
                $this->redirect(array('/apps/buy/' . CHtml::encode($model->id) . '/' . CHtml::encode($model->title)));
        }
    }

    protected function download($fileName, $filePath)
    {
        $fakeFileName = $fileName;
        $realFileName = $fileName;

        $file = $filePath . DIRECTORY_SEPARATOR . $realFileName;
        $fp = fopen($file, 'rb');

        $mimeType = '';
        switch (pathinfo($fileName, PATHINFO_EXTENSION)) {
            case 'apk':
                $mimeType = 'application/vnd.android.package-archive';
                break;

            case 'xap':
                $mimeType = 'application/x-silverlight-app';
                break;

            case 'ipa':
                $mimeType = 'application/octet-stream';
                break;
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename=' . $fakeFileName);

        echo stream_get_contents($fp);
    }

    /**
     * Show programs list
     */
    public function actionPrograms($id = null, $title = null)
    {
        if (is_null($id))
            $id = 1;
        $this->showCategory($id, $title, 'برنامه ها');
    }

    /**
     * Show games list
     */
    public function actionGames($id = null, $title = null)
    {
        if (is_null($id))
            $id = 2;
        $this->showCategory($id, $title, 'بازی ها');
    }

    /**
     * Show educations list
     */
    public function actionEducations($id = null, $title = null)
    {
        if (is_null($id))
            $id = 3;
        $this->showCategory($id, $title, 'آموزش ها');
    }

    /**
     * Show programs list
     *
     * @param $title string
     * @param $id integer
     */
    public function actionDeveloper($title, $id = null)
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';
        $criteria = new CDbCriteria();
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('platform_id=:platform');
        if (isset($_GET['t']) and $_GET['t'] == 1) {
            $criteria->addCondition('developer_team=:dev');
            $developer_id=$pageTitle = $title;
        } else {
            $criteria->addCondition('developer_id=:dev');
            $developer_id = $id;
            $pageTitle = UserDetails::model()->findByAttributes(array('user_id' => $id));
            $pageTitle=$pageTitle->nickname;
        }
        $criteria->params = array(
            ':confirm' => 'accepted',
            ':deleted' => 0,
            ':status' => 'enable',
            ':platform' => $this->platform,
            ':dev' => $developer_id,
        );
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->order = 'id DESC';
        $dataProvider = new CActiveDataProvider('Apps', array(
            'criteria' => $criteria,
        ));

        $this->render('_app_list_manual', array(
            'dataProvider' => $dataProvider,
            'title' => $pageTitle,
            'pageTitle' => 'برنامه ها'
        ));
    }

    /**
     * Show apps list of category
     */
    public function showCategory($id, $title, $pageTitle)
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';

        $criteria = new CDbCriteria();
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('platform_id=:platform');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->params = array(
            ':confirm' => 'accepted',
            ':deleted' => 0,
            ':status' => 'enable',
            ':platform' => $this->platform,
        );

        $categories = AppCategories::model()->getCategoryChilds($id);
        $criteria->addInCondition('category_id', $categories);
        $criteria->order = 'id DESC';
        $criteria->limit = '40';
        $latest = new CActiveDataProvider('Apps', array(
            'criteria' => $criteria,
        ));


        $criteria = new CDbCriteria();
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('platform_id=:platform');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->params = array(
            ':confirm' => 'accepted',
            ':deleted' => 0,
            ':status' => 'enable',
            ':platform' => $this->platform,
        );

        $categories = AppCategories::model()->getCategoryChilds($id);
        $criteria->addInCondition('category_id', $categories);
        $criteria->addCondition('ratings.rate IS NOT NULL');
        $criteria->select = array('t.*', 'AVG(ratings.rate) AS avgRate');
        $criteria->with[] = 'ratings';
        $criteria->together = true;
        $criteria->order = 'avgRate DESC';
        $criteria->limit = '40';
        $criteria->group = 't.id';
        $topRates = new CActiveDataProvider('Apps', array(
            'criteria' => $criteria,
        ));

        $categories = AppCategories::model()->getCategoryChilds($id);
        $criteria->addInCondition('category_id', $categories);
        $criteria->addCondition('price = 0');
        $criteria->order = 'id DESC';
        $criteria->limit = '40';
        $free = new CActiveDataProvider('Apps', array(
            'criteria' => $criteria,
        ));

        $this->render('apps_list', array(
            'latest' => $latest,
            'topRates' => $topRates,
            'free' => $free,
            'title' => (!is_null($title)) ? $title : null,
            'pageTitle' => $pageTitle
        ));
    }

    /**
     * Bookmark app
     */
    public function actionBookmark()
    {
        Yii::app()->getModule('users');
        $model = UserAppBookmark::model()->find('user_id=:user_id AND app_id=:app_id', array(':user_id' => Yii::app()->user->getId(), ':app_id' => $_POST['appId']));
        if (!$model) {
            $model = new UserAppBookmark();
            $model->app_id = $_POST['appId'];
            $model->user_id = Yii::app()->user->getId();
            if ($model->save())
                echo CJSON::encode(array(
                    'status' => true
                ));
            else
                echo CJSON::encode(array(
                    'status' => false
                ));
        } else {
            if (UserAppBookmark::model()->deleteAllByAttributes(array('user_id' => Yii::app()->user->getId(), 'app_id' => $_POST['appId'])))
                echo CJSON::encode(array(
                    'status' => true
                ));
            else
                echo CJSON::encode(array(
                    'status' => false
                ));
        }
    }

    /**
     * Report sales
     */
    public function actionReportSales()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column2';

        $labels = $values = array();
        $showChart = false;
        $activeTab = 'monthly';
        if (isset($_POST['show-chart-monthly'])) {
            $activeTab = 'monthly';
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $_POST['month_altField'], false), JalaliDate::date('m', $_POST['month_altField'], false), 1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = '';
            if (JalaliDate::date('m', $_POST['month_altField'], false) <= 6)
                $endTime = $startTime + (60 * 60 * 24 * 31);
            else
                $endTime = $startTime + (60 * 60 * 24 * 30);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime,
                ':end_date' => $endTime,
            );
            $report = AppBuys::model()->findAll($criteria);
            // show daily report
            $daysCount = (JalaliDate::date('m', $_POST['month_altField'], false) <= 6) ? 31 : 30;
            for ($i = 0; $i < $daysCount; $i++) {
                $labels[] = JalaliDate::date('d F Y', $startTime + (60 * 60 * (24 * $i)));
                $count = 0;
                foreach ($report as $model) {
                    if ($model->date >= $startTime + (60 * 60 * (24 * $i)) and $model->date < $startTime + (60 * 60 * (24 * ($i + 1))))
                        $count++;
                }
                $values[] = $count;
            }
        } elseif (isset($_POST['show-chart-yearly'])) {
            $activeTab = 'yearly';
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $_POST['year_altField'], false), 1, 1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = $startTime + (60 * 60 * 24 * 365);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime,
                ':end_date' => $endTime,
            );
            $report = AppBuys::model()->findAll($criteria);
            // show monthly report
            $tempDate = $startTime;
            for ($i = 0; $i < 12; $i++) {
                if ($i < 6)
                    $monthDaysCount = 31;
                else
                    $monthDaysCount = 30;
                $labels[] = JalaliDate::date('F', $tempDate);
                $tempDate = $tempDate + (60 * 60 * 24 * ($monthDaysCount));
                $count = 0;
                foreach ($report as $model) {
                    if ($model->date >= $startTime + (60 * 60 * 24 * ($monthDaysCount * $i)) and $model->date < $startTime + (60 * 60 * 24 * ($monthDaysCount * ($i + 1))))
                        $count++;
                }
                $values[] = $count;
            }
        } elseif (isset($_POST['show-chart-by-program'])) {
            $activeTab = 'by-program';
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addCondition('app_id=:app_id');
            $criteria->params = array(
                ':from_date' => $_POST['from_date_altField'],
                ':to_date' => $_POST['to_date_altField'],
                ':app_id' => $_POST['app_id'],
            );
            $report = AppBuys::model()->findAll($criteria);
            if ($_POST['to_date_altField'] - $_POST['from_date_altField'] < (60 * 60 * 24 * 30)) {
                // show daily report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $daysCount = ($datesDiff / (60 * 60 * 24));
                for ($i = 0; $i < $daysCount; $i++) {
                    $labels[] = JalaliDate::date('d F Y', $_POST['from_date_altField'] + (60 * 60 * (24 * $i)));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_altField'] + (60 * 60 * (24 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * (24 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            } else {
                // show monthly report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $monthCount = ceil($datesDiff / (60 * 60 * 24 * 30));
                for ($i = 0; $i < $monthCount; $i++) {
                    $labels[] = JalaliDate::date('d F', $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i))) . ' الی ' . JalaliDate::date('d F', $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1))));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            }
        } elseif (isset($_POST['show-chart-by-developer'])) {
            $activeTab = 'by-developer';
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addInCondition('app_id', CHtml::listData(Apps::model()->findAllByAttributes(array('developer_id' => $_POST['developer'])), 'id', 'id'));
            $criteria->params[':from_date'] = $_POST['from_date_developer_altField'];
            $criteria->params[':to_date'] = $_POST['to_date_developer_altField'];
            $report = AppBuys::model()->findAll($criteria);
            if ($_POST['to_date_developer_altField'] - $_POST['from_date_developer_altField'] < (60 * 60 * 24 * 30)) {
                // show daily report
                $datesDiff = $_POST['to_date_developer_altField'] - $_POST['from_date_developer_altField'];
                $daysCount = ($datesDiff / (60 * 60 * 24));
                for ($i = 0; $i < $daysCount; $i++) {
                    $labels[] = JalaliDate::date('d F Y', $_POST['from_date_developer_altField'] + (60 * 60 * (24 * $i)));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_developer_altField'] + (60 * 60 * (24 * $i)) and $model->date < $_POST['from_date_developer_altField'] + (60 * 60 * (24 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            } else {
                // show monthly report
                $datesDiff = $_POST['to_date_developer_altField'] - $_POST['from_date_developer_altField'];
                $monthCount = ceil($datesDiff / (60 * 60 * 24 * 30));
                for ($i = 0; $i < $monthCount; $i++) {
                    $labels[] = JalaliDate::date('d F', $_POST['from_date_developer_altField'] + (60 * 60 * 24 * (30 * $i))) . ' الی ' . JalaliDate::date('d F', $_POST['from_date_developer_altField'] + (60 * 60 * 24 * (30 * ($i + 1))));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_developer_altField'] + (60 * 60 * 24 * (30 * $i)) and $model->date < $_POST['from_date_developer_altField'] + (60 * 60 * 24 * (30 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            }
        }

        $this->render('report_sales', array(
            'labels' => $labels,
            'values' => $values,
            'showChart' => $showChart,
            'activeTab' => $activeTab,
        ));
    }

    /**
     * Report income
     */
    public function actionReportIncome()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column2';

        $labels = $values = array();
        $sumIncome = $sumCredit = 0;
        $showChart = false;
        $sumCredit = UserDetails::model()->find(array('select' => 'SUM(credit) AS credit'));
        $sumCredit = $sumCredit->credit;
        if (isset($_POST['show-chart-monthly'])) {
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $_POST['month_altField'], false), JalaliDate::date('m', $_POST['month_altField'], false), 1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = '';
            if (JalaliDate::date('m', $_POST['month_altField'], false) <= 6)
                $endTime = $startTime + (60 * 60 * 24 * 31);
            else
                $endTime = $startTime + (60 * 60 * 24 * 30);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime,
                ':end_date' => $endTime,
            );
            $report = AppBuys::model()->findAll($criteria);
            Yii::app()->getModule('setting');
            $commission = SiteSetting::model()->findByAttributes(array('name' => 'commission'));
            $commission = $commission->value;
            // show daily report
            $daysCount = (JalaliDate::date('m', $_POST['month_altField'], false) <= 6) ? 31 : 30;
            for ($i = 0; $i < $daysCount; $i++) {
                $labels[] = JalaliDate::date('d F Y', $startTime + (60 * 60 * (24 * $i)));
                $amount = 0;
                foreach ($report as $model) {
                    if ($model->date >= $startTime + (60 * 60 * (24 * $i)) and $model->date < $startTime + (60 * 60 * (24 * ($i + 1))))
                        $amount = $model->app->price;
                }
                $values[] = ($amount * $commission) / 100;
                $sumIncome += ($amount * $commission) / 100;
            }
        }

        $this->render('report_income', array(
            'labels' => $labels,
            'values' => $values,
            'showChart' => $showChart,
            'sumIncome' => $sumIncome,
            'sumCredit' => $sumCredit,
        ));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionSearch()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/public';
        $criteria = new CDbCriteria();
        $criteria->addCondition('platform_id=:platform_id AND status=:status AND confirm=:confirm AND deleted=:deleted AND (SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->params[':platform_id'] = $this->platform;
        $criteria->params[':status'] = 'enable';
        $criteria->params[':confirm'] = 'accepted';
        $criteria->params[':deleted'] = 0;
        $criteria->limit = 20;
        $criteria->order = 't.id DESC';
        if (isset($_GET['term']) && !empty($term = $_GET['term'])) {
            $terms = explode(' ', urldecode($term));
            $sql = null;
            foreach ($terms as $key => $term)
                if ($term) {
                    if (!$sql)
                        $sql = "(";
                    else
                        $sql .= " OR (";
                    $sql .= "t.title regexp :term$key OR t.description regexp :term$key OR category.title regexp :term$key)";
                    $criteria->params[":term$key"] = $term;
                }
            $criteria->with[] = 'category';
            $criteria->addCondition($sql);

        }
        $dataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        $this->render('search', array(
            'dataProvider' => $dataProvider
        ));
    }

    /**
     * Show apps list of category
     */
    public function actionDiscount()
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';
        $criteria = new CDbCriteria();
        $criteria->with[] = 'app';
        $criteria->addCondition('app.confirm=:confirm');
        $criteria->addCondition('app.deleted=:deleted');
        $criteria->addCondition('app.status=:status');
        $criteria->addCondition('app.platform_id=:platform');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=app.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=app.id) != 0');
        $criteria->addCondition('start_date < :now AND end_date > :now');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=app.id) != 0');
        $criteria->params = array(
            ':confirm' => 'accepted',
            ':deleted' => 0,
            ':status' => 'enable',
            ':platform' => $this->platform,
            ':now' => time()
        );
        $criteria->order = 'app.id DESC';
        $dataProvider = new CActiveDataProvider('AppDiscounts', array(
            'criteria' => $criteria,
        ));

        $this->render('apps_discounts_list', array(
            'dataProvider' => $dataProvider,
            'pageTitle' => 'تخفیفات'
        ));
    }

    /**
     * @param $app_id
     * @param $rate
     * @throws CException
     * @throws CHttpException
     */
    public function actionRate($app_id, $rate)
    {
        $model = $this->loadModel($app_id);
        if ($model) {
            $rateModel = new AppRatings();
            $rateModel->rate = (int)$rate;
            $rateModel->app_id = $model->id;
            $rateModel->user_id = Yii::app()->user->getId();
            if ($rateModel->save()) {
                $this->beginClip('rate-view');
                $this->renderPartial('_rating', array(
                    'model' => $model
                ));
                $this->endClip();
                if (isset($_GET['ajax'])) {
                    echo CJSON::encode(array('status' => true, 'rate' => $rateModel->rate, 'rate_wrapper' => $this->clips['rate-view']));
                    Yii::app()->end();
                }
            } else {
                if (isset($_GET['ajax'])) {
                    echo CJSON::encode(array('status' => false, 'msg' => 'متاسفانه عملیات با خطا مواجه است! لطفا مجددا سعی فرمایید.'));
                    Yii::app()->end();
                }
            }
        } else {
            if (isset($_GET['ajax'])) {
                echo CJSON::encode(array('status' => false, 'msg' => 'مقادیر ارسالی صحیح نیست.'));
                Yii::app()->end();
            }
        }
    }

    public function actionTop()
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';

        $catIds = AppCategories::model()->getCategoryChilds(1);
        $criteria = new CDbCriteria();
        $criteria->select = 't.*, AVG(ratings.rate) as avgRate';
        $criteria->with = array('images', 'ratings');
        $criteria->together = true;
        $criteria->addInCondition('category_id', $catIds);
        $criteria->addCondition('platform_id=:platform_id');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->addCondition('ratings.rate IS NOT NULL');
        $criteria->params[':platform_id'] = $this->platform;
        $criteria->params[':status'] = 'enable';
        $criteria->params[':confirm'] = 'accepted';
        $criteria->params[':deleted'] = 0;
        $criteria->limit = 20;
        $criteria->order = 'avgRate DESC, t.id DESC';
        $criteria->group = 't.id';
        $dataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        $this->render('_app_list_manual', array(
            'dataProvider' => $dataProvider,
            'title' => null,
            'pageTitle' => 'برترین ها'
        ));
    }

    public function actionBestselling()
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';

        $catIds = AppCategories::model()->getCategoryChilds(1);
        $criteria = new CDbCriteria();
        $criteria->with = array('images', 'appBuys' => array('joinType' => 'RIGHT OUTER JOIN'));
        $criteria->together = true;
        $criteria->addInCondition('category_id', $catIds);
        $criteria->addCondition('platform_id=:platform_id');
        $criteria->addCondition('status=:status');
        $criteria->addCondition('confirm=:confirm');
        $criteria->addCondition('deleted=:deleted');
        $criteria->addCondition('(SELECT COUNT(app_images.id) FROM ym_app_images app_images WHERE app_images.app_id=t.id) != 0');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->params[':platform_id'] = $this->platform;
        $criteria->params[':status'] = 'enable';
        $criteria->params[':confirm'] = 'accepted';
        $criteria->params[':deleted'] = 0;
        $criteria->limit = 20;
        $criteria->order = 'COUNT(appBuys.id) DESC';
        $criteria->group = 'appBuys.app_id';
        $dataProvider = new CActiveDataProvider('Apps', array('criteria' => $criteria));

        $this->render('_app_list_manual', array(
            'dataProvider' => $dataProvider,
            'title' => null,
            'pageTitle' => 'پرفروش ترین ها'
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Apps the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Apps::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}