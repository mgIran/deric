<?php
class PDatePicker extends CInputWidget
{
    protected $publishedAssetsPath;
    public $model;
    public $id;
    public $options;
    public $htmlOptions;

    public function init()
    {
        if(Yii::getPathOfAlias('PDatePicker') === false) Yii::setPathOfAlias('PDatePicker', realpath(dirname(__FILE__) . '/..'));
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($this->getAssetsUrl().'/css/persian-datepicker-0.4.5.min.css');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/persian-date.js');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/persian-datepicker-0.4.5.min.js');

        if(!isset($this->options['altField']))
        {
            $this->options['altField']='#'.$this->id.'_altField';
            $this->options['altFormat']='X';
        }
        $js = "$('#$this->id').persianDatepicker(".CJavaScript::encode($this->options).");";
        $cs->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);
        echo CHtml::textField($this->id, '', $this->htmlOptions);
        echo CHtml::hiddenField($this->id.'_altField');
    }

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath = Yii::getPathOfAlias('ext.PDatePicker.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }
}