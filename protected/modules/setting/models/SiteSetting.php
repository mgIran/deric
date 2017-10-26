<?php

/**
 * This is the model class for table "ym_site_setting".
 *
 * The followings are the available columns in table 'ym_site_setting':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $value
 */
class SiteSetting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_site_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, title, value', 'required'),
			array('name, title', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, value', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'title' => 'Title',
			'value' => 'Value',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('value', $this->value, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiteSetting the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getOption($name, $scalar = true)
	{
		return $scalar?self::model()->findByAttributes(array('name' => $name))->value:self::model()->findByAttributes(array('name' => $name));
	}

	public static function setOption($name, $value, $title = null)
	{
		$model = self::getOption($name, false);
		if($model === null){
			$model = new SiteSetting();
			$model->name = $name;
		}
		$model->value = $value;
		if($title)
			$model->title = $title;
		return $model->save();
	}
}