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
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css?2.2');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css?2.2');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.countdown.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js');
    ?>
</head>
<body>
<?= $this->renderPartial('//layouts/_header'); ?>
<div class="mobail visible-xs">
    <div class="mobail-nav">
        <div class="svg-bars">
            <a href="#"></a>
            <span class="glyphicon icon-bars"></span>
        </div>
    </div>
    <div class="mobail-menu">
        <div class="mobail-body">
            <ul>
                <li class="clike-user dash"><a href="#" class=""></a><span class="glyphicon i-one"></span>داشبورد</li>
                <li class="clike-user trans"><a href="#" class=""></a><span class="glyphicon i-two"></span>تراکنش ها</li>
                <li class="clike-user boy"><a href="#" class=""></a><span class="glyphicon i-three"></span>خریدها</li>
                <li class="clike-user hert"><a href="#" class=""></a><span class="glyphicon i-four"></span>نشان شده ها</li>
                <li class="clike-user sup"><a href="#" class=""></a><span class="glyphicon i-five"></span>پشتیبانی</li>
                <li class="clike-user set"><a href="#" class=""></a><span class="glyphicon i-six"></span>تنظیمات</li>
            </ul>
        </div>
        <div class="mobail-heading">
            <h4 class="bottoms">
                <a class="link-head" href="#">
                    <span class="text">توسعه دهندگان</span>
                    <span class="glyphicon icon-down"></span>
                </a>
            </h4>
        </div>
    </div>
</div>
<div class="consumer">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
            <div class="panel-group karbar-panel">
                <div class="panel panel-item">
                    <div id="collapse1" class="panel">
                        <div class="panel-body collapse-one">
                            <div class="img-user">
                                <img src="<?= Yii::app()->user->avatar ?>">
                                <span><?= Yii::app()->user->fa_name ?></span>
                            </div>
                            <div class="icon-user">
                                <span class="glyphicon user"></span>
                                <span><?= Yii::app()->user->role_name ?></span>
                            </div>
                            <div class="email-user">
                                <span class="glyphicon email"></span>
                                <span><?= Yii::app()->user->email ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-heading head-panel">
                    <h4 class="panel-title bottoms">
                        <a class="link-head" href="#" data-toggle="collapse" data-target="#user-menu">
                            <span class="text">کاربری</span>
                            <span class="glyphicon up icon"></span>
                        </a>
                    </h4>
                </div>
                <div class="panel panel-item hidden-xs collapse" id="user-menu">
                    <div class="panel">
                        <div class="panel-body collapse-two">
                            <ul>
                                <li class="clike-user dash"><a href="#" class=""></a><span class="glyphicon i-one"></span>داشبورد</li>
                                <li class="clike-user trans"><a href="#" class=""></a><span class="glyphicon i-two"></span>تراکنش ها</li>
                                <li class="clike-user boy"><a href="#" class=""></a><span class="glyphicon i-three"></span>خریدها</li>
                                <li class="clike-user hert"><a href="#" class=""></a><span class="glyphicon i-four"></span>نشان شده ها</li>
                                <li class="clike-user sup"><a href="#" class=""></a><span class="glyphicon i-five"></span>پشتیبانی</li>
                                <li class="clike-user set"><a href="#" class=""></a><span class="glyphicon i-six"></span>تنظیمات</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-heading head-panel">
                    <h4 class="panel-title bottoms">
                        <a class="link-head" href="#" data-toggle="collapse" data-target="#developer-menu">
                            <span class="text">توسعه دهندگان</span>
                            <span class="glyphicon down icon"></span>
                        </a>
                    </h4>
                </div>
                <div class="panel panel-item hidden-xs collapse" id="developer-menu">
                    <div class="panel">
                        <div class="panel-body collapse-three">
                            <ul>
                                <li class="clike-user dash"><a href="#" class=""></a><span class="glyphicon i-one"></span>داشبورد</li>
                                <li class="clike-user trans"><a href="#" class=""></a><span class="glyphicon i-two"></span>تراکنش ها</li>
                                <li class="clike-user boy"><a href="#" class=""></a><span class="glyphicon i-three"></span>خریدها</li>
                                <li class="clike-user hert"><a href="#" class=""></a><span class="glyphicon i-four"></span>نشان شده ها</li>
                                <li class="clike-user sup"><a href="#" class=""></a><span class="glyphicon i-five"></span>پشتیبانی</li>
                                <li class="clike-user set"><a href="#" class=""></a><span class="glyphicon i-six"></span>تنظیمات</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 hrefs">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<div class="footer user-footer">
    <div class="menu">
        <ul class="menu-footer">
            <li><a href="#">حریم شخصی</a></li>
            <li><a href="#">شرایط استفاده</a></li>
            <li><a href="#">توسعه دهندگان</a></li>
            <li><a href="#">درباره ما</a></li>
            <li><a href="#">تماس با ما</a></li>
        </ul>
    </div>
    <div class="copyright">
        <div class="text-center">کپی رایت @ سیسن اپ - 2018-1396 - تمامی حقوق محفوظ است.</div>
    </div>
</div>
</body>
</html>