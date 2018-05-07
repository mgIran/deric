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
    $cs->registerCssFile($baseUrl.'/css/bootstrap-select.min.css');
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/animate.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css?2.3.1');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css?2.3');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/bootstrap-select.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.countdown.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js');
    ?>
</head>
<body>
<?= $this->renderPartial('//layouts/_header'); ?>
<div class="consumer">
    <div class="row">
        <div class="col-lg-3 col-md-3 panel-sidebar">
            <div class="panel-group karbar-panel">
                <div class="panel panel-item">
                    <div id="collapse1" class="panel">
                        <div class="panel-body collapse-one">
                            <div class="img-user">
                                <?php if(Yii::app()->user->avatar): echo CHtml::image(Yii::app()->getBaseUrl(true).'/uploads/users/avatar/'.Yii::app()->user->avatar); else: echo '<span class="default-user"></span>'; endif; ?>
                                <span><?= Yii::app()->user->fa_name ?></span>
                            </div>
                            <div class="user-icon">
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
                <div class="panel panel-item">
                <div class="panel-heading head-panel">
                    <h4 class="panel-title bottoms">
                        <a class="link-head">
                            <span class="text">کاربری</span>
                            <span class="glyphicon down icon"></span>
                        </a>
                    </h4>
                </div>
                <div class="panel panel-item" id="user-menu">
                    <div class="panel">
                        <div class="panel-body collapse-two">
                            <ul>
                                <li class="clike-user dash"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=credit-tab");?>"></a><span class="glyphicon i-one"></span>داشبورد</li>
                                <li class="clike-user trans"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=transactions-tab");?>"></a><span class="glyphicon i-two"></span>تراکنش ها</li>
                                <li class="clike-user boy"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=buys-tab");?>"></a><span class="glyphicon i-three"></span>خریدها</li>
                                <li class="clike-user hert"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=bookmarks-tab");?>"></a><span class="glyphicon i-four"></span>نشان شده ها</li>
                                <li class="clike-user sup"><a href="<?php echo $this->createUrl('/tickets/manage/'); ?>"></a><span class="glyphicon i-five"></span>پشتیبانی</li>
                                <li class="clike-user sup"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=profile-tab");?>"></a><span class="glyphicon i-five"></span>تغییر مشخصات کاربری</li>
                                <li class="clike-user set"><a href="<?php echo Yii::app()->createUrl("/dashboard?tab=setting-tab");?>"></a><span class="glyphicon i-six"></span>تنظیمات</li>
                                <?php if(Yii::app()->user->roles!='developer'):?>
                                    <li class="clike-user developer-link">
                                        <a href="<?php echo Yii::app()->createUrl("/developers/panel/signup/step/agreement");?>"></a>
                                        <span class="glyphicon white-user-icon"></span>
                                        <span>توسعه دهنده شوید</span>
                                    </li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
                <?php if(Yii::app()->user->roles == 'developer'):?>
                <div class="panel panel-item">
                <div class="panel-heading head-panel">
                    <h4 class="panel-title bottoms">
                        <a class="link-head">
                            <span class="text">توسعه دهندگان</span>
                            <span class="glyphicon down icon"></span>
                        </a>
                    </h4>
                </div>
                <div class="panel panel-item" id="developer-menu">
                    <div class="panel">
                        <div class="panel-body collapse-two">
                            <ul>
                                <li class="clike-user phone-icon"><a href="<?php echo $this->createUrl('/developers/panel');?>"></a><span class="glyphicon i-one"></span>برنامه ها</li>
                                <li class="clike-user discount-icon"><a href="<?php echo $this->createUrl('/developers/panel/discount');?>"></a><span class="glyphicon i-two"></span>تخفیفات</li>
                                <li class="clike-user user-icon"><a href="<?php echo $this->createUrl('/developers/panel/account');?>"></a><span class="glyphicon i-three"></span>حساب توسعه دهنده</li>
                                <li class="clike-user chart-icon"><a href="<?php echo $this->createUrl('/developers/panel/sales');?>"></a><span class="glyphicon i-four"></span>گزارش فروش</li>
                                <li class="clike-user payment-icon"><a href="<?php echo $this->createUrl('/developers/panel/settlement');?>"></a><span class="glyphicon i-five"></span>تسویه حساب</li>
                                <li class="clike-user sup"><a href="<?php echo $this->createUrl('/tickets/manage?dev=1');?>"></a><span class="glyphicon i-five"></span>پشتیبانی</li>
                                <li class="clike-user books-icon"><a href="<?php echo $this->createUrl('/developers/panel/documents');?>"></a><span class="glyphicon i-six"></span>مستندات</li>
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 hrefs">
            <div class="hidden-lg hidden-md">
                <a id="panel-menu-trigger" class="btn btn-default" href="#"><i class="glyphicon glyphicon-menu-hamburger"></i> منو</a>
            </div>
            <div class="clearfix"></div>
            <?php echo $content; ?>
        </div>
        <div class="menu-overlay"></div>
    </div>
</div>
<div class="clearfix"></div>
<div class="footer user-footer">
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