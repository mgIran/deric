<?php

/**
 * This is the model class for table "ym_users".
 *
 * The followings are the available columns in table 'ym_users':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $role_id
 * @property string $create_date
 * @property string $status
 * @property string $verification_token
 * @property integer $change_password_request_count
 * @property string $repeatPassword
 * @property string $oldPassword
 * @property string $newPassword
 *
 * The followings are the available model relations:
 * @property AppBuys[] $appBuys
 * @property Apps[] $apps
 * @property Apps[] $bookmarkedApps
 * @property UserDetails $userDetails
 * @property UserDevIdRequests $userDevIdRequests
 * @property UserTransactions[] $transactions
 * @property UserRoles $role
 */
class Users extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ym_users';
    }

    public $statusLabels = array(
        'pending' => 'در انتظار تایید',
        'active' => 'فعال',
        'blocked' => 'مسدود',
        'deleted' => 'حذف شده'
    );
    public $fa_name;
    public $statusFilter;
    public $repeatPassword;
    public $oldPassword;
    public $newPassword;
    public $roleId;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, password', 'required', 'on' => 'insert,create'),
            array('role_id', 'default', 'value' => 1),
            array('email', 'required', 'on' => 'email'),
            array('email', 'unique', 'on' => 'insert,create'),
            array('change_password_request_count', 'numerical', 'integerOnly' => true),
            array('email', 'email'),
            array('oldPassword ,newPassword ,repeatPassword', 'required', 'on' => 'update'),
            array('password', 'required', 'on' => 'change_password'),
            array('repeatPassword', 'compare', 'compareAttribute' => 'password', 'on' => 'change_password'),
            array('email', 'filter', 'filter' => 'trim', 'on' => 'create'),
            array('username, password, verification_token', 'length', 'max' => 100, 'on' => 'create'),
            array('oldPassword', 'oldPass', 'on' => 'update'),
            array('email', 'length', 'max' => 255),
            array('role_id', 'length', 'max' => 10),
            array('status', 'length', 'max' => 8),
            array('create_date', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('roleId, create_date, status, verification_token, change_password_request_count ,fa_name ,email ,statusFilter', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check this username is exist in database or not
     */
    public function oldPass($attribute, $params)
    {
        $bCrypt = new bCrypt();
        $record = Users::model()->findByAttributes(array('email' => $this->email));
        if (!$bCrypt->verify($this->$attribute, $record->password))
            $this->addError($attribute, 'کلمه عبور فعلی اشتباه است');
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'appBuys' => array(self::HAS_MANY, 'AppBuys', 'user_id'),
            'apps' => array(self::HAS_MANY, 'Apps', 'developer_id'),
            'userDetails' => array(self::HAS_ONE, 'UserDetails', 'user_id'),
            'userDevIdRequests' => array(self::HAS_ONE, 'UserDevIdRequests', 'user_id'),
            'transactions' => array(self::HAS_MANY, 'UserTransactions', 'user_id'),
            'role' => array(self::BELONGS_TO, 'UserRoles', 'role_id'),
            'bookmarkedApps' => array(self::MANY_MANY, 'Apps', '{{user_app_bookmark}}(user_id,app_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
            'role_id' => 'نقش',
            'email' => 'پست الکترونیک',
            'repeatPassword' => 'تکرار کلمه عبور',
            'oldPassword' => 'کلمه عبور فعلی',
            'newPassword' => 'کلمه عبور جدید',
            'create_date' => 'تاریخ ثبت نام',
            'status' => 'وضعیت کاربر',
            'verification_token' => 'Verification Token',
            'change_password_request_count' => 'تعداد درخواست تغییر کلمه عبور',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('status', $this->statusFilter, true);
        $criteria->compare('verification_token', $this->verification_token, true);
        $criteria->compare('change_password_request_count', $this->change_password_request_count);
        $criteria->addSearchCondition('role.id', $this->roleId);
        $criteria->addSearchCondition('userDetails.fa_name', $this->fa_name);
        $criteria->addCondition('status!=:status');
        $criteria->params[':status'] = 'deleted';
        $criteria->with = array('role', 'userDetails');
        $criteria->order = 'status ,t.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
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

    public function afterSave()
    {
        if (parent::afterSave()) {
            if ($this->isNewRecord) {
                $model = new UserDetails;
                $model->user_id = $this->id;
                $model->credit = 0;
                $model->save();
            }
        }
        return true;
    }

    public function getDeveloers()
    {
        $criteria = new CDbCriteria;

        $criteria->addCondition('role_id=2');
        $criteria->with = 'userDetails';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}