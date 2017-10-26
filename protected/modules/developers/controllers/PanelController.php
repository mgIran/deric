<?php

class PanelController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/panel';
    
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
        return array(
            array('allow',
                'actions'=>array('manageSettlement','excel'),
                'roles'=>array('admin', 'finance')
            ),
            array('allow',
                'actions'=>array('uploadNationalCardImage', 'uploadRegistrationCertificateImage'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('signup'),
                'roles'=>array('user'),
            ),
            array('allow',
                'actions'=>array('account','index', 'discount','settlement','sales','documents'),
                'roles'=>array('developer'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
	{
        Yii::app()->theme='market';
        $criteria=new CDbCriteria();
        $criteria->addCondition('developer_id = :user_id');
        $criteria->addCondition('deleted = 0');
        //$criteria->addCondition('title != ""');
        $criteria->addCondition('(SELECT COUNT(app_packages.id) FROM ym_app_packages app_packages WHERE app_packages.app_id=t.id) != 0');
        $criteria->params=array(':user_id'=>Yii::app()->user->getId());
        $appsDataProvider=new CActiveDataProvider('Apps', array(
            'criteria'=>$criteria,
        ));
        Yii::app()->getModule('users');

		$this->render('index', array(
            'appsDataProvider'=>$appsDataProvider,
        ));
	}


	public function actionDocuments()
	{
        Yii::app()->theme='market';
        Yii::app()->getModule("pages");
        $criteria=new CDbCriteria();
        $criteria->addCondition('category_id = 2');
        $documentsProvider=new CActiveDataProvider('Pages', array(
            'criteria'=>$criteria,
        ));
		$this->render('documents', array(
            'documentsProvider'=>$documentsProvider,
        ));
	}

    public function actionDiscount()
	{
        Yii::app()->theme='market';
        $model = new AppDiscounts();

        if(isset($_GET['ajax']) && $_GET['ajax'] === 'apps-discount-form') {
            $model->attributes = $_POST['AppDiscounts'];
            $errors = CActiveForm::validate($model);
            if(CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }

        if(isset($_POST['AppDiscounts']))
        {
            $model->attributes =$_POST['AppDiscounts'];
            if($model->save())
            {
                if(isset($_GET['ajax'])) {
                    echo CJSON::encode(array('state' => 'ok','msg' => 'تخفیف با موفقیت اعمال شد.'));
                    Yii::app()->end();
                } else {
                    Yii::app()->user->setFlash('discount-success','تخفیف با موفقیت اعمال شد.');
                    $this->refresh();
                }
            }
            else
                Yii::app()->user->setFlash('discount-failed','متاسفانه در انجام درخواست مشکلی ایجاد شده است.');
        }

        $criteria=new CDbCriteria();
        $criteria->with[] = 'app';
        $criteria->addCondition('app.developer_id = :user_id');
        $criteria->addCondition('app.deleted = 0');
        $criteria->addCondition('app.title != ""');
        $criteria->addCondition('end_date > :now');
        $criteria->params=array(
            ':user_id'=>Yii::app()->user->getId(),
            ':now' => time()
        );
        $appsDataProvider=new CActiveDataProvider('AppDiscounts', array(
            'criteria'=>$criteria,
        ));

        // delete expire discounts
        $criteria=new CDbCriteria();
        $criteria->addCondition('end_date < :now');
        $criteria->params=array(
            ':now' => time()
        );
        AppDiscounts::model()->deleteAll($criteria);
        //

        Yii::app()->getModule('users');

        $criteria=new CDbCriteria();
        $criteria->addCondition('developer_id = :user_id');
        $criteria->addCondition('deleted = 0');
        $criteria->addCondition('price != 0');
        $criteria->addCondition('title != ""');
        $criteria->with[] = 'discount';
        $criteria->addCondition('discount.app_id IS NULL');
        $criteria->params=array(':user_id'=>Yii::app()->user->getId());

        $apps = CHtml::listData(Apps::model()->findAll($criteria),'id' ,'title');

        $this->render('discount', array(
            'appsDataProvider'=>$appsDataProvider,
            'apps' => $apps
        ));
	}

    /**
     * Update account
     */
    public function actionAccount()
    {
        Yii::app()->theme='market';
        Yii::import('application.modules.users.models.*');

        $detailsModel=UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));
        $devIdRequestModel=UserDevIdRequests::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));
        if($detailsModel->developer_id=='' && is_null($devIdRequestModel))
            $devIdRequestModel=new UserDevIdRequests;

        $detailsModel->scenario='update_'.$detailsModel->type.'_profile';

        if(isset($_POST['ajax']) && $_POST['ajax']==='change-developer-id-form')
            $this->performAjaxValidation($devIdRequestModel);
        else
            $this->performAjaxValidation($detailsModel);

        // Save developer profile
        if(isset($_POST['UserDetails']))
        {
            unset($_POST['UserDetails']['credit']);
            unset($_POST['UserDetails']['developer_id']);
            unset($_POST['UserDetails']['details_status']);
            $detailsModel->attributes=$_POST['UserDetails'];
            $detailsModel->details_status='pending';
            if($detailsModel->save())
            {
                Yii::app()->user->setFlash('success' , 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }
            else
                Yii::app()->user->setFlash('failed' , 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        // Save the change request developerID
        if(isset($_POST['UserDevIdRequests']))
        {
            $devIdRequestModel->user_id=Yii::app()->user->getId();
            $devIdRequestModel->requested_id=$_POST['UserDevIdRequests']['requested_id'];
            if($devIdRequestModel->save())
            {
                Yii::app()->user->setFlash('success' , 'شناسه درخواستی ثبت گردید و در انتظار تایید می باشد.');
                $this->refresh();
            }
            else
                Yii::app()->user->setFlash('failed' , 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $nationalCardImageUrl=$this->createUrl('/uploads/users/national_cards');
        $nationalCardImagePath=Yii::getPathOfAlias('webroot').'/uploads/users/national_cards';
        $nationalCardImage=array();
        if($detailsModel->national_card_image!='')
            $nationalCardImage=array(
                'name' => $detailsModel->national_card_image,
                'src' => $nationalCardImageUrl.'/'.$detailsModel->national_card_image,
                'size' => (file_exists($nationalCardImagePath.'/'.$detailsModel->national_card_image))?filesize($nationalCardImagePath.'/'.$detailsModel->national_card_image):0,
                'serverName' => $detailsModel->national_card_image,
            );

        $registrationCertificateImageUrl=$this->createUrl('/uploads/users/registration_certificate');
        $registrationCertificateImagePath=Yii::getPathOfAlias('webroot').'/uploads/users/registration_certificate';
        $registrationCertificateImage=array();
        if($detailsModel->registration_certificate_image!='')
            $registrationCertificateImage=array(
                'name' => $detailsModel->registration_certificate_image,
                'src' => $registrationCertificateImageUrl.'/'.$detailsModel->registration_certificate_image,
                'size' => (file_exists($registrationCertificateImagePath.'/'.$detailsModel->registration_certificate_image))?filesize($registrationCertificateImagePath.'/'.$detailsModel->registration_certificate_image):0,
                'serverName' => $detailsModel->registration_certificate_image,
            );

        $this->render('account', array(
            'detailsModel'=>$detailsModel,
            'devIdRequestModel'=>$devIdRequestModel,
            'nationalCardImage'=>$nationalCardImage,
            'registrationCertificateImage'=>$registrationCertificateImage,
        ));
    }

    /**
     * Upload national card image
     */
    public function actionUploadNationalCardImage()
    {
        $uploadDir = Yii::getPathOfAlias("webroot").'/uploads/users/national_cards/';
        if (!is_dir(Yii::getPathOfAlias("webroot").'/uploads/users/'))
            mkdir(Yii::getPathOfAlias("webroot").'/uploads/users/');
        if (!is_dir($uploadDir))
            mkdir($uploadDir);
        if (isset($_FILES)) {
            Yii::import('application.modules.users.models.*');
            $model = UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));

            $file = $_FILES['national_card_image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file['name'] = Controller::generateRandomString(5) . time();
            $file['name'] = $file['name'].'.'.$ext;
            if(move_uploaded_file($file['tmp_name'], $uploadDir.CHtml::encode($file['name'])))
            {
                $response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];

                // Delete old image
                if(!empty($model->national_card_image))
                    @unlink($uploadDir.$model->national_card_image);

                $model->national_card_image=$file['name'];
                $model->scenario='upload_photo';
                $model->save();

                // Resize image
                $imager = new Imager();
                $imageInfo=$imager->getImageInfo($uploadDir.$model->national_card_image);
                if($imageInfo['width']>500 || $imageInfo['height']>500)
                    $imager->resize($uploadDir.$model->national_card_image, $uploadDir.$model->national_card_image, 500, 500);
            }
            else
                $response = ['state' => 'error', 'msg' => 'فایل آپلود نشد.'];
        } else
            $response = ['state' => 'error', 'msg' => 'فایلی ارسال نشده است.'];
        echo CJSON::encode($response);
        Yii::app()->end();
    }

    /**
     * Upload registration certificate image
     */
    public function actionUploadRegistrationCertificateImage()
    {
        $uploadDir = Yii::getPathOfAlias("webroot").'/uploads/users/registration_certificate/';
        if (!is_dir(Yii::getPathOfAlias("webroot").'/uploads/users/'))
            mkdir(Yii::getPathOfAlias("webroot").'/uploads/users/');
        if (!is_dir($uploadDir))
            mkdir($uploadDir);
        if (isset($_FILES)) {
            Yii::import('application.modules.users.models.*');
            $model = UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));

            $file = $_FILES['registration_certificate_image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file['name'] = Controller::generateRandomString(5) . time();
            $file['name'] = $file['name'].'.'.$ext;
            if(move_uploaded_file($file['tmp_name'], $uploadDir.CHtml::encode($file['name'])))
            {
                $response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];

                // Delete old image
                if(!empty($model->registration_certificate_image))
                    @unlink($uploadDir.$model->registration_certificate_image);

                $model->registration_certificate_image=$file['name'];
                $model->scenario='upload_photo';
                $model->save();

                // Resize image
                $imager = new Imager();
                $imageInfo=$imager->getImageInfo($uploadDir.$model->registration_certificate_image);
                if($imageInfo['width']>500 || $imageInfo['height']>500)
                    $imager->resize($uploadDir.$model->registration_certificate_image, $uploadDir.$model->registration_certificate_image, 500, 500);
            }
            else
                $response = ['state' => 'error', 'msg' => 'فایل آپلود نشد.'];
        } else
            $response = ['state' => 'error', 'msg' => 'فایلی ارسال نشده است.'];
        echo CJSON::encode($response);
        Yii::app()->end();
    }

    /**
     * Convert user account to developer
     */
    public function actionSignup()
    {
        Yii::app()->theme='market';
        $data=array();

        switch(Yii::app()->request->getQuery('step'))
        {
            case 'agreement':
                Yii::import('application.modules.pages.models.*');
                $data['agreementText']=Pages::model()->find('title=:title', array(':title'=>'قرارداد توسعه دهندگان'));
                break;

            case 'profile':
                Yii::import('application.modules.users.models.*');
                Yii::import('application.modules.setting.models.*');
                $data['detailsModel']=UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));
                $minCredit=SiteSetting::model()->find('name=:name', array(':name'=>'min_credit'));

                if(is_null($data['detailsModel']->credit))
                    $data['detailsModel']->credit=0;

                if($data['detailsModel']->credit < $minCredit['value'])
                {
                    Yii::app()->user->setFlash('min_credit_fail' , 'برای ثبت نام به عنوان توسعه دهنده باید حداقل '.number_format($minCredit['value'], 0).' تومان اعتبار داشته باشید.');
                    $this->redirect($this->createUrl('/users/credit/buy'));
                }

                if(isset($_POST['ajax']) && ($_POST['ajax']==='update-real-profile-form' || $_POST['ajax']==='update-legal-profile-form')) {
                    $data['detailsModel']->scenario='update_'.$_POST['UserDetails']['type'].'_profile';
                    $this->performAjaxValidation($data['detailsModel']);
                }

                // Save developer profile
                if(isset($_POST['UserDetails']))
                {
                    $data['detailsModel']->scenario='update_'.$_POST['UserDetails']['type'].'_profile';
                    unset($_POST['UserDetails']['credit']);
                    unset($_POST['UserDetails']['developer_id']);
                    unset($_POST['UserDetails']['details_status']);
                    $data['detailsModel']->attributes=$_POST['UserDetails'];
                    $data['detailsModel']->details_status='pending';
                    if($data['detailsModel']->save())
                    {
                        $data['detailsModel']->user->role_id=2;
                        $data['detailsModel']->user->scenario='change_role';
                        $data['detailsModel']->user->save(false);
                        Yii::app()->user->setFlash('success' , 'اطلاعات با موفقیت ثبت شد.');
                        $this->redirect($this->createUrl('/developers/panel/signup/step/finish'));
                    }
                    else
                        Yii::app()->user->setFlash('failed' , 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                }
                $nationalCardImageUrl=$this->createUrl('/uploads/users/national_cards');
                $nationalCardImagePath=Yii::getPathOfAlias('webroot').'/uploads/users/national_cards';
                $data['nationalCardImage']=array();
                if($data['detailsModel']->national_card_image!='')
                    $data['nationalCardImage']=array(
                        'name' => $data['detailsModel']->national_card_image,
                        'src' => $nationalCardImageUrl.'/'.$data['detailsModel']->national_card_image,
                        'size' => (file_exists($nationalCardImagePath.'/'.$data['detailsModel']->national_card_image))?filesize($nationalCardImagePath.'/'.$data['detailsModel']->national_card_image):0,
                        'serverName' => $data['detailsModel']->national_card_image,
                    );
                $registrationCertificateImageUrl=$this->createUrl('/uploads/users/registration_certificate');
                $registrationCertificateImagePath=Yii::getPathOfAlias('webroot').'/uploads/users/registration_certificate';
                $data['registrationCertificateImage']=array();
                if($data['detailsModel']->registration_certificate_image!='')
                    $data['registrationCertificateImage']=array(
                        'name' => $data['detailsModel']->registration_certificate_image,
                        'src' => $registrationCertificateImageUrl.'/'.$data['detailsModel']->registration_certificate_image,
                        'size' => (file_exists($registrationCertificateImagePath.'/'.$data['detailsModel']->registration_certificate_image))?filesize($registrationCertificateImagePath.'/'.$data['detailsModel']->registration_certificate_image):0,
                        'serverName' => $data['detailsModel']->registration_certificate_image,
                    );
                break;

            case 'finish':
                if(isset($_POST['goto_developer_panel']))
                {
                    Yii::app()->user->setState('roles', 'developer');
                    $this->redirect($this->createUrl('/developers/panel'));
                }
                Yii::import('application.modules.users.models.*');
                $data['userDetails']=UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));
                break;
        }

        $this->render('signup', array(
            'step'=>Yii::app()->request->getQuery('step'),
            'data'=>$data,
        ));
    }

    /**
     * Settlement
     */
    public function actionSettlement()
    {
        Yii::app()->theme='market';
        $this->layout='//layouts/panel';

        Yii::app()->getModule('users');
        Yii::app()->getModule('pages');
        $userDetailsModel=UserDetails::model()->findByAttributes(array('user_id'=>Yii::app()->user->getId()));
        $helpText=Pages::model()->findByPk(6);
        $userDetailsModel->setScenario('update-settlement');
        // Get history of settlements
        $criteria=new CDbCriteria();
        $criteria->addCondition('user_id=:user_id');
        $criteria->params=array(':user_id'=>Yii::app()->user->getId());
        $settlementHistory=new CActiveDataProvider('UserSettlement', array(
            'criteria'=>$criteria,
        ));

        $this->performAjaxValidation($userDetailsModel);

        if(isset($_POST['UserDetails'])) {
            $userDetailsModel->monthly_settlement=$_POST['UserDetails']['monthly_settlement'];
            if($_POST['UserDetails']['monthly_settlement']==1)
                $userDetailsModel->iban=$_POST['UserDetails']['iban'];
            else
                $userDetailsModel->iban=null;
            if($userDetailsModel->save())
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }

        $purifier=new CHtmlPurifier();

        $this->render('settlement', array(
            'userDetailsModel'=>$userDetailsModel,
            'helpText'=>$purifier->purify($helpText->summary),
            'settlementHistory'=>$settlementHistory,
            'formDisabled'=>(JalaliDate::date('d', time(), false)<20)?false:true,
        ));
    }

    /**
     * Report sales
     */
    public function actionSales()
    {
        Yii::app()->theme='market';
        $this->layout='//layouts/panel';

        // user's apps
        $criteria=new CDbCriteria();
        $criteria->addCondition('developer_id=:dev_id');
        $criteria->addCondition('title!=""');
        $criteria->params=array(':dev_id'=>Yii::app()->user->getId());
        $apps=new CActiveDataProvider('Apps', array(
            'criteria'=>$criteria,
        ));

        $labels = $values = array();
        if(isset($_POST['show-chart'])) {
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
            }
            else {
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
        }else{
            $userApps=Apps::model()->findAllByAttributes(array('developer_id'=>Yii::app()->user->getId()));
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addInCondition('app_id',CHtml::listData($userApps,'id','id'));
            $criteria->params[':from_date'] = strtotime(date('Y/m/d 00:00:01'));
            $criteria->params[':to_date'] = strtotime(date('Y/m/d 23:59:59'));
            $report = AppBuys::model()->findAll($criteria);
            for ($i = 0; $i < count($userApps); $i++) {
                $labels[] = CHtml::encode($userApps[$i]->title);
                $count = 0;
                foreach ($report as $model) {
                    if ($model->app_id == $userApps[$i]->id)
                        $count++;
                }
                $values[] = $count;
            }
        }

        $this->render('sales',array(
            'apps'=>$apps,
            'labels'=>$labels,
            'values'=>$values,
        ));
    }

    /**
     * Manage settlement
     */
    public function actionManageSettlement()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column2';

        if (isset($_POST['token'])) {
            if (!isset($_POST['token']) or $_POST['token'] == '') {
                Yii::app()->user->setFlash('failed', 'کد رهگیری نمی تواند خالی باشد.');
                $this->refresh();
            }
            if (!isset($_POST['amount']) or $_POST['amount'] == '') {
                Yii::app()->user->setFlash('failed', 'مبلغ تسویه نمی تواند خالی باشد.');
                $this->refresh();
            }
            $amount = (double)$_POST['amount'];
            if (!$amount) {
                Yii::app()->user->setFlash('failed', 'مبلغ تسویه نامعتبر است. لطفا مبلغ را با دقت وارد کنید.');
                $this->refresh();
            }
            $iban = $_POST['iban'];
            if(!$iban){
                Yii::app()->user->setFlash('failed', 'شماره شبا نمی تواند خالی باشد.');
                $this->refresh();
            }

            $userDetails=UserDetails::model()->findByAttributes(array('user_id'=>$_POST['user_id']));
            /* @var $userDetails UserDetails */
            $model=new UserSettlement();
            $model->user_id=$userDetails->user_id;
            $model->token=$_POST['token'];
            $model->iban=$iban;
            $model->amount= $amount;
            $model->date=time();
            if($model->save()) {
                $userDetails->earning = $userDetails->earning - $amount;
                $userDetails->setScenario('change-earning');
                $userDetails->save();
                $this->createLog('مبلغ ' . Controller::parseNumbers(number_format($model->amount)) . ' تومان در تاریخ ' .
                    JalaliDate::date('Y/m/d - H:i', $model->date) .
                    ' با کد رهگیری ' . $model->token . ' به شماره شبای IR' . $model->iban . ' واریز شد.', $userDetails->user_id);
                // Send email
                $message = '<p style="text-align: right;">با سلام<br>طبق درخواست تسویه حساب خودکار شما، مبلغ '.Controller::parseNumbers(number_format($model->amount, 0)).' تومان به شبای IR'.$model->iban.' در تاریخ '.JalaliDate::date('d F Y - H:i', $model->date).' واریز گردید.<br>این رسید جهت اطلاع شما صادر گردیده است.<br>امیدواریم که کسب و کار شما رونق بیشتری بیابد و همواره سیر صعودی داشته باشید.<br><br>با احترام</p>';
                Mailer::mail($userDetails->user->email, 'رسید تسویه حساب', $message, Yii::app()->params['noReplyEmail'], Yii::app()->params['SMTP']);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }

        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(amount) AS amount, date';
        $criteria->group = 'EXTRACT(DAY FROM FROM_UNIXTIME(date, "%Y %D %M %h:%i:%s %x"))';
        $settlementHistory = new CActiveDataProvider('UserSettlement', array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 20)
        ));


        Yii::app()->getModule('setting');
        $setting = SiteSetting::model()->find('name=:name', array(':name' => 'min_credit'));
        $criteria = new CDbCriteria();
        $criteria->addCondition('iban IS NOT NULL AND iban != ""');
        $criteria->addCondition('earning > :earning');
        $criteria->addCondition('monthly_settlement=1');
        $criteria->params = array(':earning' => $setting->value);
        $settlementRequiredUsers = new CActiveDataProvider('UserDetails', array(
            'criteria' => $criteria,
        ));

        $this->render('manage_settlement', array(
            'settlementHistory' => $settlementHistory,
            'settlementRequiredUsers' => $settlementRequiredUsers,
        ));
    }

    /**
     * export excel
     */
    public function actionExcel()
    {
        Yii::app()->getModule('setting');
        $setting = SiteSetting::model()->find('name=:name', array(':name' => 'min_credit'));
        $criteria = new CDbCriteria();
        $criteria->addCondition('iban IS NOT NULL AND iban != ""');
        $criteria->addCondition('earning > :earning');
        $criteria->addCondition('monthly_settlement=1');
        $criteria->params = array(':earning' => $setting->value);
        $settlementUsers = UserDetails::model()->findAll($criteria);
        if($settlementUsers){
            $objPHPExcel = Yii::app()->yexcel->createPHPExcel();
            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Hyperapps Website")
                ->setLastModifiedBy("")
                ->setTitle("YiiExcel Test Document")
                ->setSubject("Settlement Users Detail");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'شماره شبا')
                ->setCellValue('B1', 'مبلغ قابل تسویه (تومان)')
                ->setCellValue('C1', 'نام صاحب حساب');

            /* @var $settlementUser UserDetails */
            foreach($settlementUsers as $key => $settlementUser){
                $row = $key + 2;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . $row, "IR" . $settlementUser->iban)
                    ->setCellValue('B' . $row, number_format($settlementUser->getSettlementAmount()))
                    ->setCellValue('C' . $row, $settlementUser->fa_name);
            }
            // Save a xls file
            $filename = 'Settlement Developers';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = Yii::app()->yexcel->createActiveSheet($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            unset($this->objWriter);
            unset($this->objWorksheet);
            unset($this->objReader);
            unset($this->objPHPExcel);
            exit();
        }
        Yii::app()->user->setFlash('failed','رکوردی جهت دریافت خروجی وجود ندارد.');
        $this->redirect(array('/developers/panel/manageSettlement'));
    }

    /**
     * Performs the AJAX validation.
     * @param Apps $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}