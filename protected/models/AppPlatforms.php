<?php

/**
 * This is the model class for table "ym_app_platforms".
 *
 * The followings are the available columns in table 'ym_app_platforms':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $file_types
 *
 * The followings are the available model relations:
 * @property AppCategories[] $appCategories
 * @property Apps[] $apps
 */
class AppPlatforms extends CActiveRecord
{
	public $platformsLabel=array(
		'android'=>'Android',
		'ios'=>'iOS',
		'windowsphone'=>'Windows Phone',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_app_platforms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, file_types', 'required'),
			array('name, title', 'length', 'max'=>255),
			array('file_types', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, file_types, title', 'safe', 'on'=>'search'),
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
			'appCategories' => array(self::HAS_MANY, 'AppCategories', 'platform_id'),
			'apps' => array(self::HAS_MANY, 'Apps', 'platform_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
            'title' => 'عنوان',
			'file_types' => 'فرمت فایل ها',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('file_types',$this->file_types,true);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppPlatforms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getUpperName()
	{
		return ucfirst($this->name);
	}
}
