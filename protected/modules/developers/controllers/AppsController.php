<?php

class AppsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $filesFolder = null;
    public $formats = null;


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
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'delete', 'uploadImage', 'deleteImage', 'upload', 'deleteUpload', 'uploadFile', 'deleteUploadFile', 'images', 'savePackage'),
                'roles' => array('developer'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (Yii::app()->user->isGuest || Yii::app()->user->type != 'admin') {
            $user = UserDetails::model()->findByPk(Yii::app()->user->getId());
            if ($user->details_status == 'refused') {
                Yii::app()->user->setFlash('failed', 'اطلاعات قرارداد شما رد شده است و نمیتوانید برنامه ثبت کنید. در صورت نیاز نسبت به تغییر اطلاعات خود اقدام کنید.');
                $this->redirect(array('/developers/panel/account'));
            } elseif ($user->details_status == 'pending') {
                Yii::app()->user->setFlash('warning', 'اطلاعات قرارداد شما در انتظار تایید می باشد،لطفا پس از تایید اطلاعات مجددا تلاش کنید.');
                $this->redirect(array('/developers/panel/account'));
            }
            if (!$user->developer_id) {
                $devIdRequestModel = UserDevIdRequests::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
                if ($devIdRequestModel)
                    Yii::app()->user->setFlash('warning', 'درخواست شما برای شناسه توسعه دهنده در انتظار تایید می باشد، لطفا شکیبا باشید.');
                else
                    Yii::app()->user->setFlash('failed', 'شناسه توسعه دهنده تنظیم نشده است. برای ثبت برنامه شناسه توسعه دهنده الزامیست.');
                $this->redirect(array('/developers/panel/account'));
            }

            Yii::app()->theme = 'market';
            $this->layout = '//layouts/panel';
            $model = new Apps;

            // Save step 1
            if (isset($_POST['platform_id']) && !empty($_POST['platform_id'])) {
                $model->platform_id = $_POST['platform_id'];
                $model->developer_id = Yii::app()->user->getId();
                if ($model->save())
                    $this->redirect('update/' . $model->id . '?new=1');
                else
                    Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');

            } elseif (isset($_POST['platform_id']) && empty($_POST['platform_id']))
                $model->addError("platform_id", 'لطفا یک گزینه را انتخاب کنید');

            $this->render('create', array(
                'model' => $model,
            ));
        } else {
            Yii::app()->user->setFlash('failed', 'از طریق مدیریت اقدام کنید');
            $this->redirect(array('/admins/dashboard'));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $step = 1;
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/panel';
        $model = $this->loadModel($id);
        if ($model->developer_id != Yii::app()->user->getId()) {
            Yii::app()->user->setFlash('images-failed', 'شما اجازه دسترسی به این صفحه را ندارید.');
            $this->redirect($this->createUrl('/developers/panel'));
        }
        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->createAbsoluteUrl('/uploads/temp/');
        $appIconsDIR = Yii::getPathOfAlias("webroot") . '/uploads/apps/icons/';
        $appImagesDIR = Yii::getPathOfAlias("webroot") . '/uploads/apps/images/';
        $appIconsUrl = Yii::app()->createAbsoluteUrl('/uploads/apps/icons');
        $appImagesUrl = Yii::app()->createAbsoluteUrl('/uploads/apps/images');
        if ($model->platform) {
            $platform = $model->platform;
            $formats = explode(',', $platform->file_types);
            if (count($formats) > 1) {
                foreach ($formats as $key => $format) {
                    $format = '.' . trim($format);
                    $formats[$key] = $format;
                }
                $this->formats = implode(',', $formats);
            } else
                $this->formats = '.' . trim($formats[0]);

            $this->filesFolder = $platform->name;
            $appFilesDIR = Yii::getPathOfAlias("webroot") . "/uploads/apps/files/{$this->filesFolder}/";
            if (!is_dir($appFilesDIR))
                mkdir($appFilesDIR);
            $appFilesUrl = Yii::app()->createAbsoluteUrl("/uploads/apps/files/{$this->filesFolder}");
        } else
            $this->redirect(array('create'));

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        $icon = array();
        if (!is_null($model->icon))
            $icon = array(
                'name' => $model->icon,
                'src' => $appIconsUrl . '/' . $model->icon,
                'size' => filesize($appIconsDIR . $model->icon),
                'serverName' => $model->icon
            );
        $images = array();
        if ($model->images)
            foreach ($model->images as $image)
                if (file_exists($appImagesDIR . $image->image))
                    $images[] = array(
                        'name' => $image->image,
                        'src' => $appImagesUrl . '/' . $image->image,
                        'size' => filesize($appImagesDIR . $image->image),
                        'serverName' => $image->image,
                    );

        if (isset($_POST['packages-submit'])) {
            if (empty($model->packages))
                Yii::app()->user->setFlash('failed', 'بسته ای تعریف نشده است.');
            else
                $this->redirect($this->createUrl('/developers/apps/update/' . $model->id . '?step=2'));
        }

        if (isset($_POST['Apps'])) {
            $iconFlag = false;
            if (isset($_POST['Apps']['icon']) && file_exists($tmpDIR . $_POST['Apps']['icon']) && $_POST['Apps']['icon'] != $model->icon) {
                $file = $_POST['Apps']['icon'];
                $icon = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                $iconFlag = true;
            }
            $model->attributes = $_POST['Apps'];
            if(isset($_POST['Apps']['permissions'])) {
                if (count($_POST['Apps']['permissions']) > 0 && !empty($_POST['Apps']['permissions'][0])) {
                    foreach ($_POST['Apps']['permissions'] as $key => $permission) {
                        if (empty($permission))
                            unset($_POST['Apps']['permissions'][$key]);
                    }
                    $model->permissions = CJSON::encode($_POST['Apps']['permissions']);
                } else
                    $model->permissions = null;
            }

//            $model->confirm = 'pending';

            if(isset($_POST['priceType'])) {
                $pt = $_POST['priceType'];
                switch ($pt) {
                    case 'free':
                        $model->price = 0;
                        break;
                    case 'online-payment':
                        break;
                    case 'in-app-payment':
                        $model->price = -1;
                        break;
                }
            }

            if ($model->save()) {
                if ($iconFlag) {
                    $thumbnail = new Imager();
                    $thumbnail->createThumbnail($tmpDIR . $model->icon, 150, 150, false, $appIconsDIR . $model->icon);
                    unlink($tmpDIR . $model->icon);
                }
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
                $this->redirect(array('/developers/apps/update/' . $model->id . '?step=3'));
            } else {
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }
        }

        if (isset($_GET['step']) && !empty($_GET['step']))
            $step = (int)$_GET['step'];

        $criteria = new CDbCriteria();
        $criteria->addCondition('app_id=:app_id');
        $criteria->params = array(
            ':app_id' => $id,
        );
        $packagesDataProvider = new CActiveDataProvider('AppPackages', array('criteria' => $criteria));

        Yii::app()->getModule('setting');
        $this->render('update', array(
            'model' => $model,
            'imageModel' => new AppImages(),
            'images' => $images,
            'icon' => $icon,
            'packagesDataProvider' => $packagesDataProvider,
            'step' => $step,
            'tax' => SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value,
            'commission' => SiteSetting::model()->findByAttributes(array('name' => 'commission'))->value,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        Apps::model()->updateByPk($id, array('deleted' => 1));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/developers/panel'));
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

    /**
     * Performs the AJAX validation.
     * @param Apps $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'apps-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * Upload And Delete App File and Icon Functions
     */
    public function actionUpload()
    {
        $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';

        if (!is_dir($tempDir))
            mkdir($tempDir);
        if (isset($_FILES)) {
            $file = $_FILES['icon'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file['name'] = Controller::generateRandomString(5) . time();
            while (file_exists($tempDir . DIRECTORY_SEPARATOR . $file['name']. '.' .$ext))
                $file['name'] = Controller::generateRandomString(5) . time();
            $file['name'] = $file['name'] . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $tempDir . DIRECTORY_SEPARATOR . CHtml::encode($file['name']))) {
                $imager = new Imager();
                $imageInfo = $imager->getImageInfo($tempDir . DIRECTORY_SEPARATOR . $file['name']);
                if ($imageInfo['width'] < 512 or $imageInfo['height'] < 512) {
                    $response = ['state' => 'error', 'msg' => 'اندازه آیکون نباید کوچکتر از 512x512 پیکسل باشد.'];
                    unlink($tempDir . DIRECTORY_SEPARATOR . $file['name']);
                } else
                    $response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];
            } else
                $response = ['state' => 'error', 'msg' => 'فایل آپلود نشد.'];
        } else
            $response = ['state' => 'error', 'msg' => 'فایلی ارسال نشده است.'];
        echo CJSON::encode($response);
        Yii::app()->end();
    }

    public function actionDeleteUpload()
    {
        $Dir = Yii::getPathOfAlias("webroot") . '/uploads/apps/icons/';

        if (isset($_POST['fileName'])) {

            $fileName = $_POST['fileName'];

            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp/';

            $model = Apps::model()->findByAttributes(array('icon' => $fileName));
            if ($model) {
                if (@unlink($Dir . $model->icon)) {
                    $model->updateByPk($model->id, array('icon' => null));
                    $response = ['state' => 'ok', 'msg' => $this->implodeErrors($model)];
                } else
                    $response = ['state' => 'error', 'msg' => 'مشکل ایجاد شده است'];
            } else {
                @unlink($tempDir . $fileName);
                $response = ['state' => 'ok', 'msg' => 'حذف شد.'];
            }
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }

    public function actionUploadFile()
    {
        if (isset($_FILES['file_name'])) {
            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
            if (!is_dir($tempDir))
                mkdir($tempDir);
            if (isset($_FILES)) {
                $file = $_FILES['file_name'];
                $file['name'] = str_replace(' ', '_', $file['name']);
                $file['name'] = time() . '-' . $file['name'];
                if (move_uploaded_file($file['tmp_name'], $tempDir . DIRECTORY_SEPARATOR . $file['name']))
                    $response = ['status' => true, 'fileName' => CHtml::encode($file['name'])];
                else
                    $response = ['status' => false, 'message' => 'در عملیات آپلود فایل خطایی رخ داده است.'];
            } else
                $response = ['status' => false, 'message' => 'فایلی ارسال نشده است.'];
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }

    public function actionDeleteUploadFile()
    {
        echo CJSON::encode(['state' => 'ok', 'msg' => 'فایل با موفقیت حذف شد.']);
    }

    /**
     * Upload app images
     */
    public function actionUploadImage()
    {
        $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
        if (!is_dir($uploadDir))
            mkdir($uploadDir);
        if (isset($_FILES)) {
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file['name'] = Controller::generateRandomString(5) . time();
            while (file_exists($uploadDir . DIRECTORY_SEPARATOR . $file['name'] . '.' . $ext))
                $file['name'] = Controller::generateRandomString(5) . time();
            $file['name'] = $file['name'] . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $uploadDir . DIRECTORY_SEPARATOR . CHtml::encode($file['name']))) {
                $response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];
            } else
                $response = ['state' => 'error', 'msg' => 'فایل آپلود نشد.'];
        } else
            $response = ['state' => 'error', 'msg' => 'فایلی ارسال نشده است.'];
        echo CJSON::encode($response);
        Yii::app()->end();
    }

    /**
     * Delete app images
     */
    public function actionDeleteImage()
    {
        if (isset($_POST['fileName'])) {

            $fileName = $_POST['fileName'];

            $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/apps/images/';
            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp/';

            $model = AppImages::model()->findByAttributes(array('image' => $fileName));
            if ($model) {
                if (unlink($uploadDir . $model->image)) {
                    $model->delete();
                    $response = ['state' => 'ok', 'msg' => $this->implodeErrors($model)];
                } else
                    $response = ['state' => 'error', 'msg' => 'مشکل ایجاد شده است'];
            } else {
                @unlink($tempDir . $fileName);
                $response = ['state' => 'ok', 'msg' => 'حذف شد.'];
            }
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }

    public function actionImages($id)
    {
        $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/apps/images/';
        if (isset($_POST['AppImages']['image'])) {
            $flag = true;
            foreach ($_POST['AppImages']['image'] as $image) {
                if (file_exists($tempDir . $image)) {
                    $model = new AppImages();
                    $model->app_id = (int)$id;
                    $model->image = $image;
                    rename($tempDir . $image, $uploadDir . $image);
                    if (!$model->save(false))
                        $flag = false;
                }
            }
            if ($flag) {
                Yii::app()->user->setFlash('images-success', 'اطلاعات با موفقیت ثبت شد.');
                Apps::model()->updateByPk($id, array('confirm'=>'pending'));
                $this->redirect($this->createUrl('/developers/panel'));
            } else
                Yii::app()->user->setFlash('images-failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        } else
            Yii::app()->user->setFlash('images-failed', 'تصاویر برنامه را آپلود کنید.');
        $this->redirect('update/' . $id . '/?step=2');
    }

    /**
     * Return APK file info
     */
    public function apkParser($filename)
    {
        Yii::import('application.modules.manageApps.components.ApkParser.*');
        $apk = new Parser($filename);
        $manifest = $apk->getManifest();


        return array(
            'package_name' => $manifest->getPackageName(),
            'version' => $manifest->getVersionName(),
            'min_sdk_level' => $manifest->getMinSdkLevel(),
            'min_sdk_platform' => $manifest->getMinSdk()->platform,
            'permissions' => $manifest->getPermissions(),
        );
    }

    /**
     * Save app package info
     */
    public function actionSavePackage()
    {
        if (isset($_POST['app_id'])) {
            $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/apps/files/' . $_POST['filesFolder'];
            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
            if (!is_dir($uploadDir))
                mkdir($uploadDir);

            $model = new AppPackages();
            $model->app_id = $_POST['app_id'];
            $model->create_date = time();
            $model->for = $_POST['for'];
            $apkInfo = null;
            if ($_POST['platform'] == 'android') {
                $apkInfo = $this->apkParser($tempDir . DIRECTORY_SEPARATOR . $_POST['Apps']['file_name']);
                $model->version_code = $apkInfo['version_code'];
                $model->version = $apkInfo['version'];
                $model->package_name = $apkInfo['package_name'];
                $model->file_name = $apkInfo['version'] . '-' . $apkInfo['package_name'] . '.' . pathinfo($_POST['Apps']['file_name'], PATHINFO_EXTENSION);
            } else {
                $model->version = $_POST['version'];
                $model->package_name = $_POST['package_name'];
                $model->file_name = $_POST['version'] . '-' . $_POST['package_name'] . '.' . pathinfo($_POST['Apps']['file_name'], PATHINFO_EXTENSION);
            }

            if ($model->save()) {
                $response = ['status' => true, 'fileName' => CHtml::encode($model->file_name)];
                rename($tempDir . DIRECTORY_SEPARATOR . $_POST['Apps']['file_name'], $uploadDir . DIRECTORY_SEPARATOR . $model->file_name);
                if ($_POST['platform'] == 'android') {
                    /* @var $app Apps */
                    $app = Apps::model()->findByPk($_POST['app_id']);
                    $app->setScenario('set_permissions');
                    $app->permissions = CJSON::encode($this->getPermissionsName($apkInfo['permissions']));
                    $app->change_log = $_POST['Apps']['change_log'];
                    $app->save();
                }
            } else {
                $response = ['status' => false, 'message' => $model->getError('package_name')];
                //unlink($tempDir . '/' . $_POST['Apps']['file_name']);
            }

            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }

    public function getPermissionsName($permissions)
    {
        $permissionsTranslate = array(
            'ACCESS_CHECKIN_PROPERTIES' => '',
            'ACCESS_COARSE_LOCATION' => 'دسترسی به مکان تقریبی',
            'ACCESS_FINE_LOCATION' => 'دسترسی به مکان دقیق',
            'ACCESS_LOCATION_EXTRA_COMMANDS' => '',
            'ACCESS_MOCK_LOCATION' => '',
            'ACCESS_NETWORK_STATE' => 'دسترسی به اطلاعات شبکه',
            'ACCESS_SURFACE_FLINGER' => '',
            'ACCESS_WIFI_STATE' => 'دسترسی به شبکه Wi-Fi',
            'ACCOUNT_MANAGER' => '',
            'ADD_VOICEMAIL' => 'افزودن پست های صوتی',
            'AUTHENTICATE_ACCOUNTS' => '',
            'BATTERY_STATS' => 'جمع آوری آمار باتری',
            'BIND_ACCESSIBILITY_SERVICE' => '',
            'BIND_APPWIDGET' => 'دسترسی به ویجت ها',
            'BIND_DEVICE_ADMIN' => '',
            'BIND_INPUT_METHOD' => '',
            'BIND_NFC_SERVICE' => '',
            'BIND_NOTIFICATION_LISTENER_SERVICE' => '',
            'BIND_PRINT_SERVICE' => '',
            'BIND_REMOTEVIEWS' => '',
            'BIND_TEXT_SERVICE' => '',
            'BIND_VPN_SERVICE' => '',
            'BIND_WALLPAPER' => '',
            'BLUETOOTH' => 'اتصال به دستگاه ها توسط بلوتوث',
            'BLUETOOTH_ADMIN' => 'مدیریت اتصال بلوتوث دستگاه ها',
            'BLUETOOTH_PRIVILEGED' => 'مدیریت اتصال بلوتوث دستگاه ها',
            'BRICK' => '',
            'BROADCAST_PACKAGE_REMOVED' => 'دسترسی به اعلان پیام حذف یک بسته',
            'BROADCAST_SMS' => 'دسترسی به اعلان پیام دریافت پیامک',
            'BROADCAST_STICKY' => '',
            'BROADCAST_WAP_PUSH' => 'دسترسی به اعلان دریافت WAP PUSH',
            'CALL_PHONE' => 'دسترسی به تماس تلفنی',
            'CALL_PRIVILEGED' => 'دسترسی به تماس تلفنی',
            'CAMERA' => 'دسترسی به دوربین',
            'CAPTURE_AUDIO_OUTPUT' => 'ضبط خروجی صدا',
            'CAPTURE_SECURE_VIDEO_OUTPUT' => 'ضبط خروجی ویدئوی امن',
            'CAPTURE_VIDEO_OUTPUT' => 'ضبط خروجی ویدئوی',
            'CHANGE_COMPONENT_ENABLED_STATE' => 'دسترسی به کامپوننت ها',
            'CHANGE_CONFIGURATION' => 'تغییر تنظیمات',
            'CHANGE_NETWORK_STATE' => 'تغییر حالت اتصال به شبکه',
            'CHANGE_WIFI_MULTICAST_STATE' => '',
            'CHANGE_WIFI_STATE' => 'تغییر اتصال شبکه Wi-Fi',
            'CLEAR_APP_CACHE' => 'حذف کش',
            'CLEAR_APP_USER_DATA' => '',
            'CONTROL_LOCATION_UPDATES' => 'دسترسی به مکان',
            'DELETE_CACHE_FILES' => 'حذف فایل های کش',
            'DELETE_PACKAGES' => 'حذف بسته ها',
            'DEVICE_POWER' => '',
            'DIAGNOSTIC' => '',
            'DISABLE_KEYGUARD' => 'غیر فعال کردن کلید محافظ',
            'DUMP' => 'دریافت وضعیت Junk',
            'EXPAND_STATUS_BAR' => 'باز و بسته کردن نوار وضعیت',
            'FACTORY_TEST' => 'اجرای آزمایشات کارخانه',
            'FLASHLIGHT' => '',
            'FORCE_BACK' => '',
            'GET_ACCOUNTS' => 'دریافت لیست حساب های کاربری',
            'GET_PACKAGE_SIZE' => 'دریافت حجم بسته ها',
            'GET_TASKS' => '',
            'GET_TOP_ACTIVITY_INFO' => '',
            'GLOBAL_SEARCH' => 'جستجو سراسری',
            'HARDWARE_TEST' => '',
            'INJECT_EVENTS' => '',
            'INSTALL_LOCATION_PROVIDER' => 'نصب توسعه دهنده مکان',
            'INSTALL_PACKAGES' => 'نصب بسته',
            'INSTALL_SHORTCUT' => 'نصب میانبر در Launcher',
            'INTERNAL_SYSTEM_WINDOW' => '',
            'INTERNET' => 'دسترسی به اینترنت',
            'KILL_BACKGROUND_PROCESSES' => 'متوقف کردن فرآیند های پس زمینه',
            'LOCATION_HARDWARE' => 'دسترسی به سخت افزار مکان یابی',
            'MANAGE_ACCOUNTS' => '',
            'MANAGE_APP_TOKENS' => '',
            'MANAGE_DOCUMENTS' => 'دسترسی به اسناد',
            'MASTER_CLEAR' => '',
            'MEDIA_CONTENT_CONTROL' => 'دسترسی به فایل های چندرسانه ای',
            'MODIFY_AUDIO_SETTINGS' => 'تغییر تنظیمات صدا',
            'MODIFY_PHONE_STATE' => 'تغییر وضعیت دستگاه',
            'MOUNT_FORMAT_FILESYSTEMS' => 'فرمت کردن حافظه ی جانبی',
            'MOUNT_UNMOUNT_FILESYSTEMS' => 'مدیریت حافظه ی جانبی',
            'NFC' => 'دسترسی به NFC',
            'PERSISTENT_ACTIVITY' => '',
            'PROCESS_OUTGOING_CALLS' => 'مدیریت تماس های خروجی',
            'READ_CALENDAR' => 'دسترسی به تقویم',
            'READ_CALL_LOG' => 'دسترسی به تاریخچه تماس',
            'READ_CONTACTS' => 'دسترسی به مخاطبین',
            'READ_EXTERNAL_STORAGE' => 'دسترسی به حافظه های خارجی',
            'READ_FRAME_BUFFER' => 'گرفتن Screen Shot',
            'READ_HISTORY_BOOKMARKS' => 'خواندن تاریخچه نشان شده ها',
            'READ_INPUT_STATE' => 'خواندن وضعیت ورودی',
            'READ_LOGS' => 'دسترسی به تاریخچه فایل ها',
            'READ_PHONE_STATE' => 'دسترسی به وضعیت دستگاه',
            'READ_PROFILE' => 'خواندن پروفایل',
            'READ_SMS' => 'دسترسی به پیامک ها',
            'READ_SOCIAL_STREAM' => '',
            'READ_SYNC_SETTINGS' => 'دسترسی به تنظیمات همگام سازی',
            'READ_SYNC_STATS' => 'دسترسی به وضعیت همگام سازی',
            'READ_USER_DICTIONARY' => '',
            'READ_SETTINGS' => 'خواندن تنظیمات',
            'UPDATE_SHORTCUT' => 'به روزرسانی میانبر',
            'REBOOT' => 'راه اندازی مجدد (Restart) دستگاه',
            'RECEIVE_BOOT_COMPLETED' => 'دریافت تکمیل شدن بودت',
            'RECEIVE_MMS' => 'دسترسی به پیام های MMS',
            'RECEIVE_SMS' => 'دریافت پیامک',
            'RECEIVE_WAP_PUSH' => 'دریافت WAP Push',
            'RECORD_AUDIO' => 'ضبط صدا',
            'REORDER_TASKS' => '',
            'RESTART_PACKAGES' => '',
            'SEND_RESPOND_VIA_MESSAGE' => 'ارسال درخواست به دیگر برنامه ها',
            'SEND_SMS' => 'ارسال پیامک',
            'SET_ACTIVITY_WATCHER' => '',
            'SET_ALARM' => 'تنظیم کردن هشدار (Alarm)',
            'SET_ALWAYS_FINISH' => '',
            'SET_ANIMATION_SCALE' => '',
            'SET_DEBUG_APP' => 'تنظیم برنامه جهت دیباگ',
            'SET_ORIENTATION' => '',
            'SET_POINTER_SPEED' => '',
            'SET_PREFERRED_APPLICATIONS' => '',
            'SET_PROCESS_LIMIT' => 'تنظیم تعداد برنامه های قابل اجرا',
            'SET_TIME' => 'تنظیم ساعت دستگاه',
            'SET_TIME_ZONE' => 'تنظیم منطقه زمانی دستگاه',
            'SET_WALLPAPER' => 'تنظیم تصویر پس زمینه',
            'SET_WALLPAPER_HINTS' => 'تنظیم تصویر پس زمینه',
            'SIGNAL_PERSISTENT_PROCESSES' => '',
            'STATUS_BAR' => 'دسترسی به نوار وضعیت و آیکون های آن',
            'SUBSCRIBED_FEEDS_READ' => '',
            'SUBSCRIBED_FEEDS_WRITE' => '',
            'SYSTEM_ALERT_WINDOW' => 'نمایش پنجره پیام',
            'TRANSMIT_IR' => '',
            'UNINSTALL_SHORTCUT' => 'حذف میانبر از Launcher',
            'UPDATE_DEVICE_STATS' => 'تغییر آمار دستگاه',
            'USE_CREDENTIALS' => 'استفاده از مجوز ها',
            'USE_SIP' => 'استفاده از سرویس SIP',
            'VIBRATE' => 'دسترسی به ویبره',
            'WAKE_LOCK' => 'ممانعت از به خواب رفتن دستگاه',
            'WRITE_APN_SETTINGS' => 'دسترسی به تنظیمات APN',
            'WRITE_CALENDAR' => 'تغییر تنظیمات تقویم',
            'WRITE_CALL_LOG' => 'ایجاد تاریخچه تماس',
            'WRITE_CONTACTS' => 'ایجاد مخاطب',
            'WRITE_EXTERNAL_STORAGE' => 'مدیریت حافظه خارجی',
            'WRITE_GSERVICES' => 'تغییر Google service map',
            'WRITE_HISTORY_BOOKMARKS' => 'دسترسی به اطلاعات مرورگر',
            'WRITE_PROFILE' => 'تغییر اطلاعات پروفایل کاربر',
            'WRITE_SECURE_SETTINGS' => 'دسترسی به تنظیمات امنیتی دستگاه',
            'WRITE_SETTINGS' => 'اعمال تنظیمات بر روی دستگاه',
            'WRITE_SMS' => 'ایجاد پیامک',
            'WRITE_SOCIAL_STREAM' => 'دسترسی به شبکه های اجتماعی کاربر',
            'WRITE_SYNC_SETTINGS' => 'اعمال تنظیمات همگام سازی',
            'WRITE_USER_DICTIONARY' => 'دسترسی به فرهنگ لغت کاربر',
        );

        $result = array();
        foreach ($permissions as $permission => $permissionData) {
            if (isset($permissionsTranslate[$permission])) {
                if ($permissionsTranslate[$permission] == '')
                    $result[] = $permission;
                else
                    $result[] = $permissionsTranslate[$permission];
            } else
                $result[] = $permission;
        }

        return $result;
    }

}