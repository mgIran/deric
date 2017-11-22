<?php

class ImagesManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
				//'accessControl', // perform access control for CRUD operations
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
				'actions' => array('createIframe', 'upload','deleteUploaded', 'delete'),
				'roles' => array('admin','developer'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Upload app images
	 */
	public function actionUpload()
	{
		$tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
		if (!is_dir($tempDir))
			mkdir($tempDir);
		if (isset($_FILES)) {
			$file = $_FILES['image'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = Controller::generateRandomString(5) . time();
			while (file_exists($tempDir . DIRECTORY_SEPARATOR . $file['name']. '.' .$ext))
				$file['name'] = Controller::generateRandomString(5) . time();
			$file['name'] = $file['name'] . '.' . $ext;
			if (move_uploaded_file($file['tmp_name'], $tempDir . DIRECTORY_SEPARATOR . CHtml::encode($file['name']))) {
				$response = ['state' => 'ok', 'fileName' => CHtml::encode($file['name'])];
				// Save image into db
				/*$model = new AppImages();
				$data = CJSON::decode($_POST['data']);
				$model->app_id = $data['app_id'];
				$model->image = $file['name'];
				$model->save();*/
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
	public function actionDeleteUploaded()
	{
		if (isset($_POST['fileName'])) {
			$fileName = $_POST['fileName'];
			$uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/apps/images/';

			$model = AppImages::model()->findByAttributes(array('image' => $fileName));
			$response = null;
			if (!is_null($model)) {
				if (@unlink($uploadDir . $fileName)) {
					$response = ['state' => 'ok', 'msg' => 'حذف شد.'];
					$model->delete();
				} else
					$response = ['state' => 'error', 'msg' => 'مشکل ایجاد شده است'];
			}
			echo CJSON::encode($response);
			Yii::app()->end();
		}
	}

	public function actionDelete($id){
		$uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/apps/images/';
		$model = AppImages::model()->findByPk($id);
        $appID = $model->app_id;
		if($model->type == 1 && file_exists($uploadDir.$model->image))
			@unlink($uploadDir.$model->image);
		$model->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('/manageApps/android/update/'.$appID.'?step=3'));
	}

	public function actionCreateIframe(){
        $model = new AppImages('insert_iframe');
        $model->type = AppImages::TYPE_IFRME;

        if(isset($_GET['ajax']) && $_GET['ajax'] === 'apps-iframe-form') {
            $model->attributes = $_POST['AppImages'];
            $errors = CActiveForm::validate($model);
            if(CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }

        if(isset($_POST['AppImages'])){
            $model->attributes = $_POST['AppImages'];
            if($model->save())
                echo CJSON::encode(['state' => 'ok', 'message' => 'ویدئو با موفقیت ثبت گردید.']);
        }
	}
}
