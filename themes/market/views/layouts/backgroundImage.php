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
    <link rel="shortcut icon" href="<?= Yii::app()->createAbsoluteUrl('themes/market/images/favicon.ico'); ?>">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/fontiran.css">
<?php
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
Yii::app()->clientScript->registerCoreScript('jquery');

$cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
$cs->registerCssFile($baseUrl.'/css/font-awesome.css');
$cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css');
$cs->registerCssFile($baseUrl.'/css/login.css');
$cs->registerCssFile($baseUrl.'/css/responsive-theme.css');

$cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
$cs->registerScriptFile($baseUrl.'/js/scripts.js');
?>
</head>
<body id="login-page">
<div class="container">
    <div class="logo-box">
        <a href="<?= Yii::app()->createAbsoluteUrl('//'); ?>">
        <img class="logo" src="<?= Yii::app()->theme->baseUrl; ?>/images/rahbod.svg" alt="Rahbod" >
        </a>
    </div>
    <?= $content; ?>
</div>
<div class="mask"></div>
</body>
</html>
