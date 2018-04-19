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
    $cs->registerCssFile($baseUrl.'/css/bootstrap-rtl.min.css');
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/animate.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css?2.3');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css?2.3');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.countdown.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js');
    ?>
</head>
<body class="bg">
<?= $this->renderPartial('//layouts/_header'); ?>
<section class="content">
    <div class="register-page">
        <div class="register-container">
            <div class="register-page-to relative">
                <?php $this->renderPartial('//layouts/_flashMessage'); ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</section>
<div class="footer register-footer">
    <div class="menu">
        <ul class="menu-footer">
            <li><a href="<?= Yii::app()->createUrl('/site/privacy'); ?>">حریم شخصی</a></li>
            <li><a href="<?= Yii::app()->createUrl('/site/terms'); ?>">شرایط استفاده</a></li>
            <li><?php if(isset(Yii::app()->user->roles) and Yii::app()->user->roles=='developer'):?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel'); ?>">توسعه دهندگان</a>
                <?php else:?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel/signup/step/agreement'); ?>">توسعه دهندگان</a>
                <?php endif;?></li>
            <li><a href="<?= Yii::app()->createUrl('/site/about');?>">درباره ما</a></li>
            <li><a href="<?= Yii::app()->createUrl('/site/contactUs'); ?>">تماس با ما</a></li>
        </ul>
    </div>
    <div class="copyright">
        <div class="text-center">کپی رایت @ سیسن اپ - 2018-1396 - تمامی حقوق محفوظ است.</div>
    </div>
</div>
</body>
</html>
