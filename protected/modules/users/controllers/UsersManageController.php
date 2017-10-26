<?php

class UsersManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction='admin';

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
			array('allow',  // allow all users to perform 'index' and 'views' actions
				'actions'=>array('index','view','create','update','admin','delete','confirmDevID','deleteDevID','confirmDeveloper','refuseDeveloper','changeStatus'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'views' page.
	 */
	public function actionCreate()
	{
		$model=new Users();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('views','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'views' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario = 'changeStatus';
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success' ,'<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
				if(isset($_POST['ajax']))
				{
					echo CJSON::encode(['status' => 'ok']);
					Yii::app()->end();
				}else
					$this->redirect(array('admin'));
			}else
			{
				Yii::app()->user->setFlash('failed' ,'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
				if(isset($_POST['ajax']))
				{
					echo CJSON::encode(['status' => 'error']);
					Yii::app()->end();
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->updateByPk($model->id,array('status' => 'deleted'));

		// if AJAX request (triggered by deletion via admin grid views), we should not redirect the browser
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
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

        $criteria=new CDbCriteria();
        $criteria->with='userDetails';
        $criteria->order='userDetails.score DESC';
        $criteria->limit=1;
		$topUser=new CActiveDataProvider('Users', array('criteria'=>$criteria, 'pagination'=>false));
        $criteria=new CDbCriteria();
        $criteria->with='userDetails';
        $criteria->order='userDetails.dev_score DESC';
        $criteria->limit=1;
		$topDeveloper=new CActiveDataProvider('Users', array('criteria'=>$criteria, 'pagination'=>false));

		$this->render('admin',array(
			'model'=>$model,
			'topUser'=>$topUser,
			'topDeveloper'=>$topDeveloper,
		));
	}

	/**
	 * Confirm requested ID of developer
	 */
	public function actionConfirmDevID($id)
	{
		$model = UserDetails::model()->findByAttributes(array('user_id' => $id));
		if($model->details_status != 'accepted')
			Yii::app()->user->setFlash('failed', 'اطلاعات توسعه دهنده مورد نظر هنوز تایید نشده است.');
		else {
			$model->scenario = 'confirmDev';
			$request = UserDevIdRequests::model()->findByAttributes(array('user_id' => $id));
			$model->developer_id = $request->requested_id;
			if($model->save()) {
				if($request->delete()) {
					$this->createLog('شناسه شما توسط مدیر سیستم تایید شد.', $model->user_id);
					Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				}
				else
					Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
			} else
				Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
		}
		$this->redirect(array('/admins'));
	}

	/**
	 * Delete requested ID of developer
	 */
	public function actionDeleteDevID($id)
	{
		$model = UserDevIdRequests::model()->findByAttributes(array('user_id' => $id));
		if ($model->delete()) {
			$this->createLog('شناسه شما توسط مدیر سیستم رد شد.', $model->user_id);
			Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
		}
		else
			Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
		$this->redirect(array('/admins'));
	}

	/**
	 * Confirm developer
	 */
	public function actionConfirmDeveloper($id)
	{
		$model=UserDetails::model()->findByAttributes(array('user_id'=>$id));
		$model->details_status = 'accepted';
		if($model->update())
		{
			$this->createLog('اطلاعات شما توسط مدیر سیستم تایید شد.', $model->user_id);
			Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
		}
		else
			Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
		$this->redirect(array('/admins'));
	}

	/**
	 * Delete developer
	 */
	public function actionRefuseDeveloper($id)
	{
		$model=UserDetails::model()->findByAttributes(array('user_id'=>$id));
		$model->details_status = 'refused';
		if($model->update())
		{
			$this->createLog('اطلاعات شما توسط مدیر سیستم رد شد.', $model->user_id);
			Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
		}
		else
			Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
		$this->redirect(array('/admins'));
	}
	
	public function actionChangeStatus($id)
	{
		$model = Users::model()->findByPk($id);
		if(isset($_GET['status']) && !empty($_GET['status']) && key_exists($_GET['status'], $model->statusLabels)){
			$model->status = $_GET['status'];
			if($model->save(false)){
				$msg = '';
				switch($model->status){
					case 'pending':
						$msg = 'حساب کاربری شما توسط مدیر تعلیق شد.';
						break;
					case 'active':
						$msg = 'حساب کاربری شما توسط مدیر تایید شد.';
						break;
					case 'blocked':
						$msg = 'حساب کاربری شما توسط مدیر مسدود شد.';
						break;
					case 'deleted':
						$msg = 'حساب کاربری شما توسط مدیر حذف شد.';
						break;
				}
				if($msg)
					$this->createLog($msg, $model->id);
				Yii::app()->user->setFlash('success', 'تغییرات با موفقیت ثبت شد.');
			}else
				Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
		}
		$this->redirect(array('view', 'id' => $id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
