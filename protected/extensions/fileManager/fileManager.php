<?php
/**
 * Created by PhpStorm.
 * User: Yusef-PC
 * Date: 12/10/2015
 * Time: 10:04 PM
 */

class fileManager extends CWidget
{
    /**
     * @var array of scripts and styles
     */
    private $_scripts;
    /**
     * @var null
     */
    public $id = null;
    /**
     * @var null
     */
    public $url = null;
    /**
     * @var string The name of the file field
     */
    public $name = null;
    /**
     * @var CModel The model for the file field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;
    /**
     * @var int
     */
    public $maxFiles = 2;
    /**
     * @var array html tag options
     */
    public $htmlOptions = array();
    /**
     * @var string specific path on server for fetch files
     */
    public $serverDir;

    /**
     * init widget
     */
    public function init()
    {
        if($this->id === null){
            throw new CHttpException(500, 'ID تنظیم نشده است.');
        }else{
            $this->id = $this->camelCase($this->id);
        }
        if($this->url === null){
            throw new CHttpException(500, 'آدرس دریافت تنظیم نشده است.');
        }
        if(!$this->serverDir)
            throw new CHttpException(404, 'مسیر دریافت فایل مشخص نشده است.');
        else if(!is_dir($this->serverDir) && !is_dir(Yii::getPathOfAlias('webroot').$this->serverDir) && !is_dir(Yii::getPathOfAlias('webroot').'/'.$this->serverDir))
            throw new CHttpException(404, 'مسیر دریافت فایل نامعتبر است.');
        else{
            if(is_dir(Yii::getPathOfAlias('webroot') . $this->serverDir))
                $this->serverDir = Yii::getPathOfAlias('webroot') . $this->serverDir;
            else if(is_dir(Yii::getPathOfAlias('webroot') . '/' . $this->serverDir))
                $this->serverDir = Yii::getPathOfAlias('webroot') . '/' . $this->serverDir;
        }
        Yii::app()->clientScript->registerCoreScript('jquery');
        $this->_scripts = array(
            'js' . DIRECTORY_SEPARATOR . 'script.js', // core Js File
            'css' . DIRECTORY_SEPARATOR . 'basic.css', // basic Css File
        );
        return parent::init();
    }

    /**
     * the appropriate Javascripts
     */
    protected function registerClientScript()
    {
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        foreach($this->_scripts as $script){
            $file = Yii::getPathOfAlias('ext.fileManager.assets') . DIRECTORY_SEPARATOR . $script;
            $type = explode(DIRECTORY_SEPARATOR, $script);
            if($type[0] === 'css')
                $cs->registerCssFile(Yii::app()->getAssetManager()->publish($file));
            else if($type[0] === 'js')
                $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($file),CClientScript::POS_END);
        }
    }

    /**
     * @param $str
     * @param array $noStrip
     * @return mixed|string Convert any string to camelCase format
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }

    /**
     * create a div to make the div into the file upload area
     */
    public function run()
    {
        $this->registerClientScript();
        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->id;
        if($this->model && $this->attribute){
            $hiddenFieldName = CHtml::activeHiddenField($this->model, $this->attribute, $this->htmlOptions);
        }else if($this->model && !$this->attribute && $this->name)
            $hiddenFieldName = CHtml::activeHiddenField($this->model, $this->name, $this->htmlOptions);
        else if(!$this->model && !$this->attribute && $this->name)
            $hiddenFieldName = CHtml::hiddenField($this->name, '', $this->htmlOptions);
        $this->render('_viewer', array(
            'id' => $this->id,
            'url' => $this->url,
            'path' => $this->serverDir,
            'hiddenField' => $hiddenFieldName,
            'maxFiles' => $this->maxFiles
        ));
    }
}