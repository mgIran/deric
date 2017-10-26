<?php

class ManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('create','update','createSpecial','updateSpecial','admin','delete','deleteSpecial','upload','deleteUpload'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateSpecial()
	{
		$model = new SpecialAdvertises();

		$tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
		if (!is_dir($tmpDIR))
			mkdir($tmpDIR);
		$tmpUrl = $this->createAbsoluteUrl('/uploads/temp/');
		$coverDIR = Yii::getPathOfAlias("webroot") . "/uploads/advertisesCover/";
		if (!is_dir($coverDIR))
			mkdir($coverDIR);
		$cover = array();

		if(isset($_POST['SpecialAdvertises'])) {
			$model->attributes = $_POST['SpecialAdvertises'];
			if(isset($_POST['SpecialAdvertises']['cover'])) {
				$file = $_POST['SpecialAdvertises']['cover'];
				$cover = array(
                    'name' => $file,
                    'src' => $tmpUrl.'/'.$file,
                    'size' => filesize($tmpDIR.$file),
                    'serverName' => $file,
				);
			}
			if($model->save()) {
				if($model->cover)
					rename($tmpDIR.$model->cover, $coverDIR.$model->cover);
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('admin'));
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create_special', array(
			'model' => $model,
			'cover' => $cover,
		));
	}

    /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Advertises();

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = $this->createAbsoluteUrl('/uploads/temp/');
        $coverDIR = Yii::getPathOfAlias("webroot") . "/uploads/advertisesCover/";
        if (!is_dir($coverDIR))
            mkdir($coverDIR);
        $cover = array();

		if(isset($_POST['Advertises'])) {
			$model->attributes = $_POST['Advertises'];

            if(isset($_POST['Advertises']['cover'])) {
                $file = $_POST['Advertises']['cover'];
                $cover = array(
                    'name' => $file,
                    'src' => $tmpUrl.'/'.$file,
                    'size' => filesize($tmpDIR.$file),
                    'serverName' => $file,
                );
            }

			if($model->save()) {
                if($model->cover)
                    rename($tmpDIR.$model->cover, $coverDIR.$model->cover);
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('admin'));
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create', array(
			'model' => $model,
            'cover' => $cover,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateSpecial($id)
	{
		$model=$this->loadModel($id, 'SpecialAdvertises');
		/* @var $model SpecialAdvertises */

		$tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
		if (!is_dir($tmpDIR))
			mkdir($tmpDIR);
		$tmpUrl = $this->createAbsoluteUrl('/uploads/temp/');

		$coverDIR = Yii::getPathOfAlias("webroot") . "/uploads/advertisesCover/";
		$coverUrl = $this->createAbsoluteUrl("/uploads/advertisesCover/");

		$cover = array();
		if($model->cover && file_exists($coverDIR . $model->cover))
			$cover = array(
					'name' => $model->cover,
					'src' => $coverUrl . '/' . $model->cover,
					'size' => filesize($coverDIR . $model->cover),
					'serverName' => $model->cover,
			);
		if(isset($_POST['SpecialAdvertises']))
		{
			$model->attributes=$_POST['SpecialAdvertises'];
			if(isset($_POST['SpecialAdvertises']['cover'])) {
				$file = $_POST['SpecialAdvertises']['cover'];
				$cover = array(
						'name' => $file,
						'src' => $tmpUrl.'/'.$file,
						'size' => filesize($tmpDIR.$file),
						'serverName' => $file,
				);
			}
			if($model->save()) {
				if($model->cover)
					rename($tmpDIR.$model->cover, $coverDIR.$model->cover);
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
				$this->redirect(array('admin'));
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}
		$this->render('update_special',array(
			'model'=>$model,
			'cover' => $cover,
		));
	}

    /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id, 'Advertises');
		/* @var $model Advertises */

		$tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
		if (!is_dir($tmpDIR))
			mkdir($tmpDIR);
		$tmpUrl = $this->createAbsoluteUrl('/uploads/temp/');

		$coverDIR = Yii::getPathOfAlias("webroot") . "/uploads/advertisesCover/";
		$coverUrl = $this->createAbsoluteUrl("/uploads/advertisesCover/");

		$cover = array();
		if($model->cover && file_exists($coverDIR . $model->cover))
			$cover = array(
                'name' => $model->cover,
                'src' => $coverUrl . '/' . $model->cover,
                'size' => filesize($coverDIR . $model->cover),
                'serverName' => $model->cover,
			);
		if(isset($_POST['Advertises']))
		{
			$model->attributes=$_POST['Advertises'];
			if(isset($_POST['Advertises']['cover'])) {
				$file = $_POST['Advertises']['cover'];
				$cover = array(
                    'name' => $file,
                    'src' => $tmpUrl.'/'.$file,
                    'size' => filesize($tmpDIR.$file),
                    'serverName' => $file,
				);
			}
			if($model->save()) {
				if($model->cover)
					rename($tmpDIR.$model->cover, $coverDIR.$model->cover);
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
				$this->redirect(array('admin'));
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}
		$this->render('update',array(
			'model'=>$model,
			'cover' => $cover,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeleteSpecial($id)
	{
		$this->loadModel($id, 'SpecialAdvertises')->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionDelete($id)
	{
		$model=$this->loadModel($id, 'Advertises');

		if($model->cover)
			@unlink(Yii::getPathOfAlias("webroot") . '/uploads/advertisesCover/'.$model->cover);

        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$specialModel=new SpecialAdvertises('search');
        $specialModel->unsetAttributes();

        $model=new Advertises('search');
        $model->unsetAttributes();

        if(isset($_GET['SpecialAdvertises']))
            $specialModel->attributes=$_GET['SpecialAdvertises'];

        if(isset($_GET['Advertises']))
            $model->attributes=$_GET['Advertises'];

		$this->render('admin',array(
			'specialModel'=>$specialModel,
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @param string $modelName name of the model to be loaded
	 * @return SpecialAdvertises the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id, $modelName)
	{
		$model=$modelName::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}



	public function actionUpload()
	{
		$tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';

		if (!is_dir($tempDir))
			mkdir($tempDir);
		if (isset($_FILES)) {
			$file = $_FILES['cover'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = Controller::generateRandomString(5) . time();
			while (file_exists($tempDir . DIRECTORY_SEPARATOR . $file['name']. '.' .$ext))
				$file['name'] = Controller::generateRandomString(5) . time();
			$file['name'] = $file['name'] . '.' . $ext;
			if (move_uploaded_file($file['tmp_name'], $tempDir . DIRECTORY_SEPARATOR . CHtml::encode($file['name']))) {
				$response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];
			}else
				$response = ['state' => 'error', 'msg' => 'فایل آپلود نشد.'];
		} else
			$response = ['state' => 'error', 'msg' => 'فایلی ارسال نشده است.'];
		echo CJSON::encode($response);
		Yii::app()->end();
	}

	public function actionDeleteUpload()
	{
		$Dir = Yii::getPathOfAlias("webroot") . '/uploads/advertisesCover/';

		if (isset($_POST['fileName'])) {

			$fileName = $_POST['fileName'];

			$tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp/';

			$model = SpecialAdvertises::model()->findByAttributes(array('cover' => $fileName));
			if ($model) {
				if (@unlink($Dir . $model->cover)) {
					$model->updateByPk($model->app_id, array('cover' => null));
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
}

