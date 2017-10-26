<?php

/**
 * This is the model class for table "ym_user_transactions".
 *
 * The followings are the available columns in table 'ym_user_transactions':
 * @property string $id
 * @property string $user_id
 * @property string $amount
 * @property string $date
 * @property string $status
 * @property string $token
 * @property string $description
 * @property string $authority
 * @property string $gateway_name
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserTransactions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_transactions}}';
	}

	public $statusLabels = [
		'unpaid' => 'پرداخت نشده',
		'paid' => 'پرداخت شده'
	];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, amount', 'length', 'max'=>10),
			array('date', 'length', 'max'=>20),
			array('status', 'length', 'max'=>6),
			array('token', 'length', 'max'=>50),
			array('authority, description, gateway_name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, amount, date, status, token, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'user_id' => 'کاربر',
			'amount' => 'مقدار',
			'date' => 'تاریخ',
			'status' => 'وضعیت',
			'token' => 'کد رهگیری',
			'description' => 'توضیحات',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserTransactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return mixed
	 */
	public function getStatusLabel(){ return $this->statusLabels[$this->status]; }
}
