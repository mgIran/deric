<?php

/**
 * This is the model class for table "{{rows_homepage}}".
 *
 * The followings are the available columns in table '{{rows_homepage}}':
 * @property string $id
 * @property string $title
 * @property string $status
 * @property string $const_query
 * @property string $query
 * @property string $const_category
 * @property string $order
 *
 * The followings are the available model relations:
 * @property RowAppCategoryRel[] $categoryIds
 * @property AppCategories[] $categories
 */
class RowsHomepage extends SortableCActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rows_homepage}}';
    }

    public $statusLabels = [
        1 => 'فعال',
        0 => 'غیر فعال'
    ];

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, status', 'required'),
            array('status, const_query', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 50),
            array('order, const_category', 'length', 'max' => 10),
            array('status', 'default', 'value' => 1),
            array('const_query', 'default', 'value' => 0),
            array('query', 'length', 'max' => 255),
            array('query', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, order, status, const_query', 'safe', 'on' => 'search'),
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
            'categoryIds' => array(self::HAS_MANY, 'RowAppCategoryRel', 'row_id'),
            'categories' => array(self::MANY_MANY, 'AppCategories', '{{row_app_category_rel}}(row_id, app_category_id)'),
            'constCategory' => array(self::BELONGS_TO, 'AppCategories', 'const_category'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'title' => 'عنوان',
            'status' => 'وضعیت',
            'const_query' => 'ردیف ثابت',
            'query' => 'کوئری',
            'const_category' => 'دسته بندی ثابت',
            'order' => 'ترتیب',
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
     * @param bool $const
     * @return CActiveDataProvider
     */
    public function search($const = false)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('const_query', $const?1:0);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchAppCategories()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('row_id', $this->id);
        return new CActiveDataProvider("RowAppCategoryRel", array(
            'criteria' => $criteria,
        ));
    }

    public function searchOtherAppCategories()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $thisBooks = CHtml::listData(RowAppCategoryRel::model()->findAll('row_id = :row_id', array(':row_id' => $this->id)), 'app_category_id', 'app_category_id');
        $criteria = AppCategories::model()->getDbCriteria();
        $criteria->addNotInCondition('t.id', $thisBooks);
        return new CActiveDataProvider("AppCategories", array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RowsHomepage|SortableCActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getStatusLabel()
    {
        return $this->statusLabels[$this->status];
    }


    public static function getActiveRows($const = false, $title = null)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('status = 1');

        if (!is_null($title)) {
            $criteria->addCondition('title = :title');
            $criteria->params[':title'] = $title;
        }

        if ($const)
            $criteria->addCondition('const_query = 1');

        $criteria->order = 't.const_query DESC, t.order';
        return $criteria;
    }

    /**
     * @param null $query
     * @param null $criteria
     *
     * @return CDbCriteria|null
     */
    public function getConstCriteria($criteria=null, $query=null)
    {
        if(!$query) $query = $this->query;
        $criteria = $criteria?$criteria:new CDbCriteria();
        switch ($query) {
            case 'latestGames':
            case 'latestPrograms':
                $criteria->order = 't.id DESC';
                break;
            case 'bestRates':
                $criteria->select = 't.*, AVG(ratings.rate) as avgRate';
                $criteria->addCondition('ratings.rate IS NOT NULL');
                $criteria->with = array('images', 'ratings');
                $criteria->together = true;
                $criteria->order = 'avgRate DESC, t.id DESC';
                $criteria->group = 't.id';
                break;
            case 'bestSelling':
                $criteria->with = array('images', 'appBuys' => array('joinType' => 'RIGHT OUTER JOIN'));
                $criteria->together = true;
                $criteria->order = 'COUNT(appBuys.id) DESC';
                $criteria->group = 'appBuys.app_id';
                break;
            default:
                break;
        }
        return $criteria;
    }

    public function getCategoryIdsArray(){
        return CHtml::listData($this->categoryIds, 'id', 'id');
    }
}