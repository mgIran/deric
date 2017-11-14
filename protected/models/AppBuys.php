<?php

/**
 * This is the model class for table "ym_app_buys".
 *
 * The followings are the available columns in table 'ym_app_buys':
 * @property string $id
 * @property string $app_id
 * @property string $user_id
 * @property string $date
 * @property string $package_version
 * @property string $package_version_code
 * @property string $app_price
 * @property string $discount_amount
 * @property string $pay_amount
 * @property string $site_earn
 * @property string $developer_earn
 *
 * The followings are the available model relations:
 * @property Apps $app
 * @property Users $user
 */
class AppBuys extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_buys}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_id, user_id', 'required'),
			array('date', 'default', 'value'=>time()),
			array('app_id, user_id', 'length', 'max'=>10),
			array('date', 'length', 'max'=>20),
			array('package_version', 'length', 'max'=>50),
			array('package_version_code, app_price, discount_amount, pay_amount', 'length', 'max'=>10),
			array('package_version_code, app_price, discount_amount, pay_amount', 'numerical', 'integerOnly'=>true),
			array('site_earn, developer_earn', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, app_id, user_id, date', 'safe', 'on'=>'search'),
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
			'app' => array(self::BELONGS_TO, 'Apps', 'app_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'app_id' => 'App',
			'user_id' => 'User',
			'date' => 'تاریخ',
			'package_version' => 'ورژن بسته',
			'package_version_code' => 'کد ورژن بسته',
			'app_price' => 'قیمت نرم افزار',
			'discount_amount' => 'درصد تخفیف',
			'pay_amount' => 'مبلغ پرداخت شده',
			'site_earn' => 'سهم دریافتی سایت',
			'developer_earn' => 'سهم دریافتی توسعه دهنده',
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
		$criteria->compare('app_id',$this->app_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppBuys the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
