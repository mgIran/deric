<?php

class AdminsManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $pageTitle = 'مدیریت مدیران سیستم';
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
				'actions'=>array('index','views','create','update','admin','delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
                'actions'=>array('index','views','create','update','admin','delete'),
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
		$this->render('views',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'views' page.
	 */
	public function actionCreate()
	{
        $this->pageTitle = 'افزودن مدیر جدید';
		$model=new Admins('create');

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Admins'])) {
            $model->attributes = $_POST[ 'Admins' ];
            if ( $model->save() )
                echo json_encode( ['result' => 'success','msg' => 'با موفقیت انجام شد' ]);
            else
                echo json_encode( ['result' => 'failed','msg' => $this->implodeErrors($model) ]);
            Yii::app()->end();
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
        $this->pageTitle = 'ویرایش مدیر';
        $model = $this->loadModel( $id );
        $model->setScenario('update');
        // Uncomment the following line if AJAX validation is needed

        $this->performAjaxValidation( $model );

        if ( isset( $_POST[ 'Admins' ] ) ) {
            $model->attributes = $_POST[ 'Admins' ];
            if($model->validate() ) {
                $model->password = $_POST[ 'Admins' ][ 'newPassword' ];
                if ( $model->save() )
                    echo json_encode( [ 'result' => 'success', 'msg' => 'با موفقیت انجام شد' ] );
                else
                    echo json_encode( [ 'result' => 'failed', 'msg' => 'متاسفانه مشکلی رخ داده است!' ] );
            }
            else
                echo json_encode( [ 'result' => 'failed', 'msg' => $this->implodeErrors($model)] );
            Yii::app()->end();
        }

        $this->render( 'update', array(
            'model' => $model,
        ) );
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        if($id != Yii::app()->user->id)
		    $this->loadModel($id)->delete();
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
		/*$dataProvider=new CActiveDataProvider('Admins');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Admins('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admins']))
			$model->attributes=$_GET['Admins'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Admins the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Admins::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admins $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admins-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
