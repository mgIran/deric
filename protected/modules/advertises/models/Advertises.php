<?php

/**
 * This is the model class for table "{{app_advertises}}".
 *
 * The followings are the available columns in table '{{app_advertises}}':
 * @property string $app_id
 * @property integer $status
 * @property string $create_date
 * @property string $cover
 *
 * The followings are the available model relations:
 * @property Apps $app
 */
class Advertises extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{app_advertises}}';
    }

    /**
     * @var $appFilter string for search app title
     */
    public $appFilter;

    public $statusLabels = array(
        '0' => 'غیر فعال',
        '1' => 'فعال'
    );

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('app_id, cover', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('app_id', 'length', 'max' => 10),
            array('create_date', 'length', 'max' => 20),
            array('create_date', 'default', 'value' => time()),
            array('cover', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('appFilter, app_id, status, create_date, cover', 'safe', 'on' => 'search'),
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
            'status' => 'وضعیت',
            'create_date' => 'تاریخ ایجاد',
            'cover' => 'تصویر',
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

        $criteria->compare('app_id', $this->app_id, true);
        $criteria->compare('t.status', $this->status);
        $criteria->with = array('app');
        $criteria->compare('app.title', $this->appFilter, true);
        $criteria->order = 'create_date DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Advertises the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}