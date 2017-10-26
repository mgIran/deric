<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="description" content="<?= $this->description?> ">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?= $this->siteName.(!empty($this->pageTitle)?' - '.$this->pageTitle:'') ?></title>
    <link rel="shortcut icon" href="<?= Yii::app()->createAbsoluteUrl('themes/market/images/favicon.png'); ?>">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/fontiran.css">
    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');

    $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css');
    $cs->registerCssFile($baseUrl.'/css/animate.min.css');
    $cs->registerCssFile($baseUrl.'/css/persian-datepicker-0.4.5.min.css');
    $cs->registerCssFile($baseUrl.'/css/persian-datepicker-custom.css');
    $cs->registerCssFile($baseUrl.'/css/svg.css');
    $cs->registerCssFile($baseUrl.'/css/panel.css');
    $cs->registerCssFile($baseUrl.'/css/panel-responsive-theme.css');

    $cs->registerCoreScript('jquery.ui');
    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/persian-datepicker-0.4.5.min.js');
    $cs->registerScriptFile($baseUrl.'/js/persian-date.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.mousewheel.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js');
    $cs->registerScriptFile($baseUrl.'/js/scripts.js');
    ?>
</head>
<body>
<?= $this->renderPartial('//layouts/_header'); ?>
<?= $this->renderPartial('//layouts/_svgDef'); ?>
<?= $this->renderPartial('//layouts/_mobile_header'); ?>
<div class="col-xs-12">
    <section class="content row">
        <?php
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            $this->renderPartial('//layouts/_panel_sidebar');
        ?>
        <div class="content-bar">
            <?php echo $content; ?>
        </div>
    </section>
</div>
<?= $this->renderPartial('//layouts/_footer'); ?>
</body>
</html>