<?php

class TicketsManageController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function beforeAction($action)
	{
		Yii::app()->theme = 'market';
		return parent::beforeAction($action);
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
				'actions'=>array('delete','pendingTicket','openTicket','admin'),
				'roles'=>array('admin','supporter'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','closeTicket','upload' ,'deleteUploaded','send'),
				'users'=>array('@'),
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
        if(!Yii::app()->user->isGuest and Yii::app()->user->type == 'admin') {
            Yii::app()->theme = 'abound';
            $this->layout = '//layouts/column2';
        }
		Yii::app()->user->returnUrl = Yii::app()->request->url;
		$model = $this->loadModel($id);
		// seen messages
		$criteria = new CDbCriteria();
		$criteria->compare('visit',0);
		$criteria->compare('ticket_id',$model->id);

		if(!Yii::app()->user->isGuest)
		{
			if(Yii::app()->user->type == 'admin')
				$criteria->compare('sender','user');
			elseif(Yii::app()->user->type == 'user')
			{
				$criteria->addCondition('sender regexp :sender');
				$criteria->params[":sender"] = "(admin|supporter)";
			}
		}
		TicketMessages::model()->updateAll(array('visit' => '1'),$criteria);

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tickets;

		if(isset($_POST['Tickets']))
		{
			$model->attributes=$_POST['Tickets'];
			if($model->save()) {
				if($model->firstMessageId)
					$this->redirect(array('view', 'id' => $model->code));
				else
					$this->redirect(array('/tickets/manage/'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionCloseTicket($id)
	{
		$model=$this->loadModel($id);
		$model->status = 'close';
		$model->update();
		if(!Yii::app()->user->isGuest && Yii::app()->user->type != 'user')
			$this->redirect(array('/tickets/'.$id));
		else
			$this->redirect(array('/tickets/manage/'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionSend()
	{
		$model=new TicketMessages;

		if(isset($_POST['TicketMessages']))
		{
			$model->attributes=$_POST['TicketMessages'];
			if($model->save()) {
                if(!Yii::app()->user->isGuest and Yii::app()->user->type == 'admin') {
					$model->ticket->updateByPk($model->ticket_id, array('status' => 'answered'));
					$this->createLog('پاسخ تیکت شماره #' . $model->ticket->code . ' توسط کارشناسان بخش پشتیبانی برای شما ارسال شد.', $model->ticket->user_id);
				}elseif(!Yii::app()->user->isGuest and Yii::app()->user->type == 'user')
                    $model->ticket->updateByPk($model->ticket_id, array('status'=>'waiting'));
                $this->redirect(array('/tickets/' . $model->ticket->code));
            }
		}
		$this->redirect(Yii::app()->user->returnUrl);
	}

	/**
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionPendingTicket($id)
	{
		$model=$this->loadModel($id);
		$model->status = 'pending';
		$model->update();
		$this->redirect(array('/tickets/'.$id));
	}

	/**
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionOpenTicket($id)
	{
		$model=$this->loadModel($id);
		$model->status = 'open';
		$model->update();
		$this->redirect(array('/tickets/'.$id));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Yii::app()->theme = 'abound';
		$this->layout = '//layouts/column1';
		$criteria = new CDbCriteria();
		if(isset($_GET['Tickets']))
			foreach($_GET['Tickets'] as $key => $param){
				$criteria->compare($key, $param, true);
			}
		$criteria->order = 'case when status regexp \'waiting\' then 1
							when status regexp \'pending\' then 2
							when status regexp \'open\' then 3
							when status regexp \'close\' then 4 end,date DESC';
		$this->render('admin', array(
			'dataProvider' => new CActiveDataProvider('Tickets', array(
				'criteria' => $criteria
			)),
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('user_id',Yii::app()->user->getId());
		$model = Tickets::model()->findAll($criteria);
		$this->render('index',array(
				'model'=>$model,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tickets the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tickets::model()->findByPk($id);
		if($model===null) {
			if(!Yii::app()->user->isGuest && Yii::app()->user->type != 'admin')
				$model = Tickets::model()->findByAttributes(array('code'=>$id ,'user_id' => Yii::app()->user->getId()));
			elseif(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin')
				$model = Tickets::model()->findByAttributes(array('code'=>$id));
			if($model===null)
				throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tickets $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tickets-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	/**
	 * Upload app images
	 */
	public function actionUpload()
	{
		$tempDir = Yii::getPathOfAlias("webroot") . '/uploads/tickets';
		if (!is_dir($tempDir))
			mkdir($tempDir);
		if (isset($_FILES)) {
			$file = $_FILES['attachment'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$file['name'] = str_ireplace(" ",'-',$file['name']);
			while (file_exists($tempDir . DIRECTORY_SEPARATOR . $file['name'])) {
				$file['name'] = Controller::generateRandomString(5).time();
				$file['name'] = $file['name'].'.'.$ext;
			}
			if (move_uploaded_file($file['tmp_name'], $tempDir . DIRECTORY_SEPARATOR . CHtml::encode($file['name']))) {
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
	public function actionDeleteUploaded()
	{
		if (isset($_POST['fileName'])) {
			$fileName = $_POST['fileName'];
			$uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/tickets/';

			$model = TicketMessages::model()->findByAttributes(array('attachment' => $fileName));
			$response = null;
			if (!is_null($model)) {
				if (is_file($uploadDir . $fileName) && @unlink($uploadDir . $fileName)) {
					$response = ['state' => 'ok', 'msg' => 'حذف شد.'];
					$model->attachment = Null;
					$model->update();
				}
				else
					$response = ['state' => 'error', 'msg' => 'مشکل ایجاد شده است'];
			}elseif (is_file($uploadDir . $fileName))
			{
				@unlink($uploadDir . $fileName);
				$response = ['state' => 'ok', 'msg' => 'حذف شد.'];
			}
			echo CJSON::encode($response);
			Yii::app()->end();
		}
	}
}
