<?php

/**
 * This is the model class for table "ym_app_discounts".
 *
 * The followings are the available columns in table 'ym_app_discounts':
 * @property string $app_id
 * @property string $start_date
 * @property string $end_date
 * @property string $percent
 * @property string $offPrice
 *
 * The followings are the available model relations:
 * @property Apps $app
 */
class AppDiscounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_app_discounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_id,percent', 'required'),
			array('app_id', 'length', 'max'=>11),
			array('start_date, end_date', 'length', 'max'=>20),
			array('start_date','compare' ,'operator' => '>=','compareValue' => time()-60*60 ,'message' => 'تاریخ شروع کمتر از حال حاضر است.'),
			array('end_date','compare' ,'operator' => '>','compareAttribute' => 'start_date','message' => 'تاریخ پایان باید از تاریخ شروع بیشتر باشد.'),
			array('percent', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('app_id, start_date, end_date, percent', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'app_id' => 'برنامه',
			'start_date' => 'تاریخ شروع',
			'end_date' => 'تاریخ پایان',
			'percent' => 'درصد',
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

		$criteria->compare('app_id',$this->app_id,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('percent',$this->percent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppDiscounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getOffPrice(){
		return $this->app->price - $this->app->price * $this->percent /100;
	}
}

