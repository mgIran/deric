<?php

/**
 * This is the model class for table "ym_app_categories".
 *
 * The followings are the available columns in table 'ym_app_categories':
 * @property string $id
 * @property string $title
 * @property string $parent_id
 * @property string $path
 *
 *
 * The followings are the available model relations:
 * @property AppCategories $parent
 * @property AppCategories[] $appCategories
 * @property Apps[] $apps
 */
class AppCategories extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_app_categories';
	}

	/**
	 * public Filter Variables for search
	 * @property string $parentFilter
	 */
	public $parentFilter;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title', 'length', 'max'=>50),
			array('title' ,'compareWithParent'),
			array('parent_id', 'length', 'max'=>10),
			array('parent_id', 'checkParent'),
            array('path', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, parent_id, parentFilter', 'safe', 'on'=>'search'),
		);
	}


	public function compareWithParent($attribute ,$params)
	{
		if(!empty($this->title) && $this->parent_id){
			$record = AppCategories::model()->findByAttributes(array('id' => $this->parent_id ,'title' => $this->title ));
			if($record)
				$this->addError($attribute ,'عنوان دسته بندی با عنوان والد یکسان است.');
		}
	}

	public function checkParent($attribute ,$params)
	{
		if(!empty($this->parent_id) && $this->id){
			if($this->parent_id == $this->id)
				$this->addError($attribute ,'دسته بندی نمی تواند زیرمجموعه خودش باشد.');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'parent' => array(self::BELONGS_TO, 'AppCategories', 'parent_id'),
			'appCategories' => array(self::HAS_MANY, 'AppCategories', 'parent_id'),
			'apps' => array(self::HAS_MANY, 'Apps', 'category_id'),
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
			'parent_id' => 'والد',
            'path' => 'مسیر'
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
		$criteria->compare('title',$this->title,true);
		$criteria->condition = 't.parent_id IS NOT NULL';
		$criteria->addSearchCondition('parent.title',$this->parentFilter);
		$criteria->with = array('parent');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' =>array(
				'pageSize' => 100
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppCategories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function sortList()
    {
        $parents = $this->findAll( 'parent_id IS NULL order by title' );
        $list = array();
        foreach ( $parents as $parent ) {
            $childes = $this->findAll($this->getCategoryChilds($parent->id ,false, 'criteria'));
            foreach ( $childes as $child ) {
                array_push( $list, $child );
            }
        }
        return CHtml::listData( $list, 'id', 'fullTitle' );
    }
	
	public function adminSortList($excludeId = NULL)
	{
		$parents = $this->findAll('parent_id IS NULL order by title');
		$list = array();
		foreach ($parents as $parent) {
			if ($parent->id != $excludeId) {
				array_push($list, $parent);
				$childes = $this->findAll($this->getCategoryChilds($parent->id, false, 'criteria'));
				foreach ($childes as $child) {
					if ($child->id != $excludeId && $child->parent_id != $excludeId)
						array_push($list, $child);
				}
			}
		}
		return CMap::mergeArray( array( '' => '-' ), CHtml::listData( $list, 'id', 'fullTitle' ) );
	}

    public function getParents( $id = NULL )
    {
        if ( $id )
            $parents = $this->findAll( 'parent_id = :id order by title', array( ':id' => $id ) );
        else
            $parents = $this->findAll( 'parent_id IS NULL order by title' );
        $list = array();
        foreach ( $parents as $parent ) {
            array_push( $list, $parent );
        }
        return CHtml::listData( $list, 'id', 'fullTitle' );
    }

    public function getFullTitle()
    {
        $fullTitle = $this->title;
        $model = $this;
        while ( $model->parent ) {
            $model = $model->parent;
            if($model->parent)
				$fullTitle = $model->title . ' - ' . $fullTitle;
			else
				$fullTitle = $fullTitle . ' ('.$model->title.')';
        }
        return $fullTitle;
    }

	public function beforeSave()
	{
		if (empty($this->parent_id))
			$this->parent_id = NULL;
		$this->path = null;
		return parent::beforeSave();
	}

	protected function afterSave()
	{
		$this->updatePath($this->id);
		return true;
	}

	public function getCategoryChilds($id = null, $withSelf = true, $returnType='array')
	{
		if ($id)
			$this->id = $id;
		$criteria = new CDbCriteria();
		$criteria->addCondition('path LIKE :regex1', 'OR');
		$criteria->addCondition('path LIKE :regex2', 'OR');
		$criteria->params[':regex1'] = $this->id . '-%';
		$criteria->params[':regex2'] = '%-' . $this->id . '-%';
		if ($withSelf) {
			$criteria->addCondition('id  = :id', 'OR');
			$criteria->params[':id'] = $this->id;
		}
		if ($returnType === 'array')
			return CHtml::listData($this->findAll($criteria), 'id', 'id');
		elseif ($returnType === 'criteria')
			return $criteria;
	}

	/**
	 * Update Path field when model parent_id is changed
	 * @param $id
	 */
	private function updatePath($id)
	{
		/* @var $model AppCategories */
		$model = AppCategories::model()->findByPk($id);
		if ($model->parent) {
			$path = $model->parent->path ? $model->parent->path . $model->parent_id . '-' : $model->parent_id . '-';
			AppCategories::model()->updateByPk($model->id,array('path' => $path));
		}
		foreach ($model->appCategories as $child)
			$this->updatePath($child->id);
	}

}
