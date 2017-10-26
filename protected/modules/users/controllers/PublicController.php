<?php

class PublicController extends Controller
{
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
            array('allow',  // allow all users to perform 'index' and 'views' actions
                'actions'=>array('dashboard','logout','setting','notifications'),
                'users' => array('@'),
            ),
            array('allow',  // allow all users to perform 'index' and 'views' actions
                'actions'=>array('register','login','verify','forgetPassword','changePassword'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Register user
     */
    public function actionRegister()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/backgroundImage';
        Yii::import('users.models.*');
        $model = new Users('create');
        if ( isset( $_POST[ 'ajax' ] ) && $_POST[ 'ajax' ] === 'register-form' ) {
            echo CActiveForm::validate( $model );
            Yii::app()->end();
        }
        if(isset($_POST['Users']))
        {
            $model->attributes = $_POST['Users'];
            $model->status='pending';
            $model->create_date=time();
            Yii::import('users.components.*');
            if($model->save())
            {
                $token=md5($model->id.'#'.$model->password.'#'.$model->email.'#'.$model->create_date);
                $model->updateByPk($model->id, array('verification_token'=>$token));
                $userDetails=new UserDetails();
                $userDetails->user_id=$model->id;
                $userDetails->credit=0;
                $userDetails->save();

                $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>برای فعال کردن حساب کاربری خود در '.Yii::app()->name.' بر روی لینک زیر کلیک کنید:</div>';
                $message .= '<div style="text-align: right;font-size: 9pt;">';
                $message .= '<a href="'.Yii::app()->getBaseUrl(true).'/users/public/verify/token/'.$token.'">'.Yii::app()->getBaseUrl(true).'/users/public/verify/token/'.$token.'</a>';
                $message .= '</div>';
                $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">این لینک فقط 3 روز اعتبار دارد.</div>';
                Mailer::mail($model->email, 'ثبت نام در '.Yii::app()->name, $message, Yii::app()->params['noReplyEmail'], Yii::app()->params['SMTP']);

                Yii::app()->user->setFlash('success' , 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.');
                $this->refresh();
            }
        }
        $this->render( 'register', array( 'model' => $model ) );
    }

    /**
     * Login Action
     */
    public function actionLogin()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/backgroundImage';
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            $this->redirect($this->createAbsoluteUrl('//'));

        $model = new UserLoginForm;
        // if it is ajax validation request
        if ( isset( $_POST[ 'ajax' ] ) && $_POST[ 'ajax' ] === 'login-form' ) {
            echo CActiveForm::validate( $model );
            Yii::app()->end();
        }

        // collect user input data
        if ( isset( $_POST[ 'UserLoginForm' ] ) ) {
            $model->attributes = $_POST[ 'UserLoginForm' ];
            // validate user input and redirect to the previous page if valid
            if ( $model->validate() && $model->login())
                $this->redirect((Yii::app()->user->returnUrl?Yii::app()->user->returnUrl:$this->createAbsoluteUrl('//')));
        }
        // display the login form
        $this->render( 'login', array( 'model' => $model ) );
    }

    /**
     * Logout Action
     */
    public function actionLogout() {
        Yii::app()->user->logout(false);
        $this->redirect(array('/login'));
    }

    /**
     * Dashboard Action
     */
    public function actionDashboard()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/panel';
        $model=Users::model()->findByPk(Yii::app()->user->getId());
        $this->render('dashboard', array(
            'model'=>$model,
        ));
    }

    /**
     * Change password
     */
    public function actionSetting()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/panel';
        $model=Users::model()->findByPk(Yii::app()->user->getId());
        $model->setScenario('update');

        $this->performAjaxValidation($model);

        if(isset($_POST['Users']))
        {
            $model->attributes=$_POST['Users'];
            if($model->validate())
            {
                $model->password=$_POST['Users']['newPassword'];
                if($model->save())
                {
                    Yii::app()->user->setFlash('success' , 'اطلاعات با موفقیت ثبت شد.');
                    $this->redirect($this->createUrl('/dashboard'));
                }
                else
                    Yii::app()->user->setFlash('failed' , 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }
        }

        $this->render('setting', array(
            'model'=>$model,
        ));
    }

    /**
     * Verify email
     */
    public function actionVerify()
    {
        if(!Yii::app()->user->isGuest and Yii::app()->user->type!='admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if(!Yii::app()->user->isGuest and Yii::app()->user->type =='admin')
        	Yii::app()->user->logout(false);    

        $token=Yii::app()->request->getQuery('token');
        $model=Users::model()->find('verification_token=:token', array(':token'=>$token));
        if($model)
        {
            if($model->status=='pending')
            {
                if(time() <= $model->create_date+259200)
                {
                    $model->updateByPk($model->id, array('status'=>'active'));
                    Yii::app()->user->setFlash('success' , 'حساب کاربری شما فعال گردید.');
                    $this->redirect($this->createUrl('/login'));
                }
                else
                {
                    Yii::app()->user->setFlash('failed' , 'لینک فعال سازی منقضی شده و نامعتبر می باشد. لطفا مجددا ثبت نام کنید.');
                    $this->redirect($this->createUrl('/register'));
                }
            }
            elseif($model->status=='active')
            {
                Yii::app()->user->setFlash('failed' , 'این حساب کاربری قبلا فعال شده است.');
                $this->redirect($this->createUrl('/login'));
            }
            else
            {
                Yii::app()->user->setFlash('failed' , 'امکان فعال سازی این کاربر وجود ندارد. لطفا مجددا ثبت نام کنید.');
                $this->redirect($this->createUrl('/register'));
            }
        }
        else
        {
            Yii::app()->user->setFlash('failed' , 'لینک فعال سازی نامعتبر می باشد.');
            $this->redirect($this->createUrl('/register'));
        }
    }

    /**
     * Forget password
     */
    public function actionForgetPassword()
    {
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/backgroundImage';
        if(!Yii::app()->user->isGuest and Yii::app()->user->type!='admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if(!Yii::app()->user->isGuest and Yii::app()->user->type =='admin')
            Yii::app()->user->logout(false);

        if(isset($_POST['email']))
        {
            $model=Users::model()->findByAttributes(array('email'=>$_POST['email']));
            if($model)
            {
                if($model->status=='active')
                {
                    if($model->change_password_request_count!=3)
                    {
                        $token=md5($model->id.'#'.$model->password.'#'.$model->email.'#'.$model->create_date.'#'.time());
                        $count=intval($model->change_password_request_count);
                        $model->updateByPk($model->id, array('verification_token'=>$token, 'change_password_request_count'=>$count+1));
                        $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>بنا به درخواست شما جهت تغییر کلمه عبور لینک زیر خدمتتان ارسال گردیده است.</div>';
                        $message .= '<div style="text-align: right;font-size: 9pt;">';
                        $message .= '<a href="'.Yii::app()->getBaseUrl(true).'/users/public/changePassword/token/'.$token.'">'.Yii::app()->getBaseUrl(true).'/users/public/changePassword/token/'.$token.'</a>';
                        $message .= '</div>';
                        $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">اگر شخص دیگری غیر از شما این درخواست را صادر نموده است، یا شما کلمه عبور خود را به یاد آورده‌اید و دیگر نیازی به تغییر آن ندارید، کلمه عبور قبلی/موجود شما همچنان فعال می‌باشد و می توانید از طریق <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/login">این صفحه</a> وارد حساب کاربری خود شوید.</div>';
                        $result=Mailer::mail($model->email, 'درخواست تغییر کلمه عبور در '.Yii::app()->name, $message, Yii::app()->params['noReplyEmail'], Yii::app()->params['SMTP']);
                        if($result)
                            echo CJSON::encode(array(
                                'hasError'=>false,
                                'message'=>'لینک تغییر کلمه عبور به '.$model->email.' ارسال شد.'
                            ));
                        else
                            echo CJSON::encode(array(
                                'hasError'=>true,
                                'message'=>'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.'
                            ));
                    }
                    else
                        echo CJSON::encode(array(
                            'hasError'=>true,
                            'message'=>'بیش از 3 بار نمی توانید درخواست تغییر کلمه عبور بدهید.'
                        ));
                }
                elseif($model->status=='pending')
                    echo CJSON::encode(array(
                        'hasError'=>true,
                        'message'=>'این حساب کاربری هنوز فعال نشده است.'
                    ));
                elseif($model->status=='blocked')
                    echo CJSON::encode(array(
                        'hasError'=>true,
                        'message'=>'این حساب کاربری مسدود می باشد.'
                    ));
                elseif($model->status=='deleted')
                    echo CJSON::encode(array(
                        'hasError'=>true,
                        'message'=>'این حساب کاربری حذف شده است.'
                    ));
            }
            else
                echo CJSON::encode(array(
                    'hasError'=>true,
                    'message'=>'پست الکترونیکی وارد شده اشتباه است.'
                ));
            Yii::app()->end();
        }

        $this->render('forget_password');
    }

    /**
     * Change password
     */
    public function actionChangePassword()
    {
        if(!Yii::app()->user->isGuest and Yii::app()->user->type!='admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if(!Yii::app()->user->isGuest and Yii::app()->user->type =='admin')
            Yii::app()->user->logout(false);

        $token=Yii::app()->request->getQuery('token');
        $model=Users::model()->find('verification_token=:token', array(':token'=>$token));

        if(!$model)
            $this->redirect($this->createAbsoluteUrl('//'));
        elseif($model->change_password_request_count==0)
            $this->redirect($this->createAbsoluteUrl('//'));

        $model->setScenario('change_password');
        $this->performAjaxValidation($model);

        if($model->status=='active') {
            Yii::app()->theme = 'market';
            $this->layout = '//layouts/backgroundImage';

            if (isset($_POST['Users'])) {
                $model->password = $_POST['Users']['password'];
                $model->repeatPassword = $_POST['Users']['repeatPassword'];
                $model->verification_token = null;
                $model->change_password_request_count = 0;
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'کلمه عبور با موفقیت تغییر یافت.');
                    $this->redirect($this->createUrl('/login'));
                } else
                    Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
            }

            $this->render('change_password', array(
                'model' => $model
            ));
        }
        else
            $this->redirect($this->createAbsoluteUrl('//'));
    }

    /**
     * List all notifications
     */
    public function actionNotifications()
    {
        $criteria=new CDbCriteria();
        $criteria->addCondition('user_id=:user_id');
        $criteria->order='id DESC';
        $criteria->params=array(
            ':user_id'=>Yii::app()->user->getId()
        );
        $model=UserNotifications::model()->findAll($criteria);
        UserNotifications::model()->updateAll(array('seen'=>'1'),'user_id=:user_id',array(':user_id'=>Yii::app()->user->getId()));
        $this->layout='//layouts/panel';
        Yii::app()->theme='market';
        $this->render('notifications',array(
            'model'=>$model
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Apps $model the model to be validated
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