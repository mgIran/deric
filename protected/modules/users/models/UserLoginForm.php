<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLoginForm extends CFormModel
{
	public $username;
    public $email;
	public $password;
	public $rememberMe;
    public $authenticate_field;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
            array('email', 'email'),
			// authenticate_field needs to be authenticated
			array('authenticate_field', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
			'rememberMe'=>'مرا بخاطر بسپار',
            'email' => 'پست الکترونیک',
            'authenticate_field' => 'Authenticate Field'
		);
	}

	/**
	 * Authenticates the authenticate_field.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity = new UserIdentity($this->email,$this->password);
            if(!$this->_identity->authenticate())
            {
                if($this->_identity->errorCode===3)
                    $this->addError($attribute,'این حساب کاربری فعال نشده است.');
                elseif($this->_identity->errorCode===4)
                    $this->addError($attribute,'این حساب کاربری مسدود شده است.');
                elseif($this->_identity->errorCode===5)
                    $this->addError($attribute,'این حساب کاربری حذف شده است.');
                else
                    $this->addError($attribute,'پست الکترونیک یا کلمه عبور اشتباه است .');
            }
		}
	}


	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
    protected function afterValidate()
    {
        $this->password = $this->encrypt($this->password);
        return parent::afterValidate();
    }

    public function encrypt($value)
    {
        $enc = NEW bCrypt();
        return $enc->hash($value);
    }
}
