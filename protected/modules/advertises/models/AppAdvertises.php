<?php

/**
 * This is the model class for table "{{app_advertises}}".
 *
 * The followings are the available columns in table '{{app_advertises}}':
 * @property string $id
 * @property string $title
 * @property string $status
 * @property string $cover
 * @property string $create_date
 * @property string $platform_id
 * @property string $app_id
 * @property string $external_details
 * @property string $fade_color
 * @property string $type
 * @property string $order
 *
 * The followings are the available model relations:
 * @property Apps $app
 * @property AppPlatforms $platform
 *
 */
class AppAdvertises extends SortableCActiveRecord
{
    const COMMON_ADVERTISE = 1;
    const SPECIAL_ADVERTISE = 2;
    const IN_APP_ADVERTISE = 3;

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

    public $typeLabels = array(
        self::COMMON_ADVERTISE => 'معمولی',
        self::SPECIAL_ADVERTISE => 'ویژه',
        self::IN_APP_ADVERTISE => 'داخل برنامه',
    );

    public $externalFields = [
        'url' => ['label' => 'لینک خارجی', 'type' => 'CHAR'],
        'size' => ['label' => 'حجم فایل', 'type' => 'CHAR'],
        'developer' => ['label' => 'توسعه دهنده', 'type' => 'CHAR'],
        'summary' => ['label' => 'توضیحات', 'type' => 'TEXT'],
        'price' => ['label' => 'قیمت', 'type' => 'FLOAT'],
        'rate' => ['label' => 'امتیاز', 'type' => 'CHAR'],
        'download' => ['label' => 'دانلود', 'type' => 'INTEGER']
    ];

    /**
     * @param $name
     * @return string|void
     */
    public function renderExtraField($name)
    {
        if (!in_array($name, array_keys($this->externalFields)))
            return;
        $value = isset($this->external_details[$name])?$this->external_details[$name]:'';
        switch ($this->externalFields[$name]['type']) {
            case 'CHAR':
                return CHtml::textField("AppAdvertises[external_details][$name]", $value ?: '', array('class' => 'form-control'));
            case 'TEXT':
                return CHtml::textArea("AppAdvertises[external_details][$name]", $value ?: '', array('class' => 'form-control'));
            case 'INTEGER':
            case 'FLOAT':
                return CHtml::numberField("AppAdvertises[external_details][$name]", $value ?: '', array('class' => 'form-control'));
        }
        return;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cover', 'required'),

            // Special Rules
            array('cover', 'required', 'on' => 'special_advertise'),
            // In-App Rules
            array('cover', 'required', 'on' => 'in_app_advertise'),
            // Common Rules
            array('cover', 'required', 'on' => 'common_advertise'),

            array('title, cover, fade_color', 'length', 'max' => 255),
            array('status, type', 'length', 'max' => 1),
            array('create_date', 'length', 'max' => 20),
            array('platform_id, app_id, order', 'length', 'max' => 10),
            array('status', 'default', 'value' => 1),
            array('create_date', 'default', 'value' => time()),
            array('external_details', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, order,status, cover, create_date, platform_id, app_id, external_details, fade_color, type', 'safe', 'on' => 'search'),
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
            'platform' => array(self::BELONGS_TO, 'AppPlatforms', 'platform_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'عنوان',
            'status' => 'وضعیت',
            'cover' => 'کاور',
            'create_date' => 'تاریخ ثبت',
            'platform_id' => 'پلتفرم',
            'app_id' => 'شناسه برنامه',
            'external_details' => 'اطلاعات خارجی',
            'fade_color' => 'رنگ زمینه',
            'type' => 'نوع آگهی',
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

        $criteria->compare('cover', $this->cover, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('t.platform_id', $this->platform_id, true);
        $criteria->compare('external_details', $this->external_details, true);
        $criteria->compare('fade_color', $this->fade_color, true);
        $criteria->compare('t.type', $this->type);

        $criteria->compare('app_id', $this->app_id, true);
        $criteria->compare('t.status', $this->status);
        $criteria->with = array('app');
        $criteria->compare('app.title', $this->appFilter, true);
        $criteria->order = 't.order';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AppAdvertises the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function findActive()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('status = 1');
        $criteria->order = 'create_date DESC';
        $criteria->limit = 1;
        return AppAdvertises::model()->find($criteria);
    }

    protected function beforeSave()
    {
        $this->external_details = is_array($this->external_details) && $this->external_details ? CJSON::encode($this->external_details) : null;

        if(isset($_GET['info']) && $_GET['info'] ==1)
            $this->external_details = null;
        else if(isset($_GET['info']) && $_GET['info'] ==2)
            $this->app_id = null;
        return parent::beforeSave(); // TODO: Change the autogenerated stub
    }

    protected function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->external_details = $this->external_details ? CJSON::decode($this->external_details) : null;
    }
}