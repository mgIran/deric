<?php

class ManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $tmpPath = 'uploads/temp';
    public $advertisePath = 'uploads/advertises';

    protected $commonOptions = array(
//        'thumbnail' => array('width' => 200, 'height' => 200),
//        'resize' => array('width' => 600, 'height' => 400)
    );
    protected $specialOptions = array(
//        'thumbnail' => array('width' => 200, 'height' => 200),
//        'resize' => array('width' => 600, 'height' => 400)
    );
    protected $inAppOptions = array(
//        'thumbnail' => array('width' => 200, 'height' => 200),
//        'resize' => array('width' => 600, 'height' => 400)
    );

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete, deleteSpecial', // we only allow deletion via POST request
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'admin', 'adminSpecial', 'adminInApp',
                    'create', 'createSpecial', 'createInApp',
                    'update',
                    'delete',
                    'upload', 'deleteUpload', 'order'),
                'roles' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'upload' => array( // list image upload
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'cover',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('png', 'jpg', 'jpeg')
                )
            ),
            'deleteUpload' => array( // delete list image uploaded
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'AppAdvertises',
                'attribute' => 'cover',
                'uploadDir' => '/uploads/advertises/',
                'storedMode' => 'field'
            ),
            'order' => array(
                'class' => 'ext.yiiSortableModel.actions.AjaxSortingAction',
            )
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateSpecial()
    {
        $model = new AppAdvertises('special_advertise');
        $model->type = AppAdvertises::SPECIAL_ADVERTISE;
        $this->create($model, 'adminSpecial');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new AppAdvertises('common_advertise');
        $model->type = AppAdvertises::COMMON_ADVERTISE;
        $this->create($model, 'admin');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateInApp()
    {
        $model = new AppAdvertises('in_app_advertise');
        $model->type = AppAdvertises::IN_APP_ADVERTISE;
        $this->create($model, 'adminInApp');
    }


    private function create($model, $redirect)
    {
        $cover = array();
        if (isset($_GET['platform_id']))
            $model->platform_id = $_GET['platform_id'];

        if (isset($_POST['AppAdvertises'])) {
            $model->attributes = $_POST['AppAdvertises'];
            if (isset($_GET['platform_id']))
                $model->platform_id = $_GET['platform_id'];
            $cover = new UploadedFiles($this->tmpPath, $model->cover, $this->commonOptions);

            if ($model->save()) {
                $cover->move($this->advertisePath);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->redirect(array($redirect));
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('create', compact('model', 'cover'));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $cover = new UploadedFiles($this->advertisePath, $model->cover);

        if (isset($_POST['AppAdvertises'])) {
            $oldCover = $model->cover;
            $model->attributes = $_POST['AppAdvertises'];
            if ($model->save()) {
                $cover->update($oldCover, $model->cover, $this->tmpPath);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
                $this->redirect(array('admin'));
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }
        $this->render('update', compact('model', 'cover'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $options = [];
        switch ($model->type) {
            case AppAdvertises::COMMON_ADVERTISE:
                $options = $this->commonOptions;
                break;
            case AppAdvertises::SPECIAL_ADVERTISE:
                $options = $this->specialOptions;
                break;
            case AppAdvertises::IN_APP_ADVERTISE:
                $options = $this->inAppOptions;
                break;
        }
        $cover = new UploadedFiles($this->advertisePath, $model->cover, $options);
        $cover->removeAll(true);
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->actionAdmin();
    }

    /**
     * Manages all common models.
     */
    public function actionAdmin()
    {
        $this->admin();
    }

    /**
     * Manages all special models.
     */
    public function actionAdminSpecial()
    {
        $this->admin(AppAdvertises::SPECIAL_ADVERTISE);
    }

    /**
     * Manages all in app models.
     */
    public function actionAdminInApp()
    {
        $this->admin(AppAdvertises::IN_APP_ADVERTISE);
    }

    public function admin($type = AppAdvertises::COMMON_ADVERTISE)
    {
        $model = new AppAdvertises('search');
        $model->unsetAttributes();

        if (isset($_GET['AppAdvertises']))
            $model->attributes = $_GET['AppAdvertises'];

        $model->type = $type;

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return AppAdvertises the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = AppAdvertises::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}