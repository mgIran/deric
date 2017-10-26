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
    $cs->registerCssFile($baseUrl.'/css/animate.min.css');
    $cs->registerCssFile($baseUrl.'/css/svg.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js');
    $cs->registerScriptFile($baseUrl.'/js/jquery.countdown.min.js');
    $cs->registerScriptFile($baseUrl.'/js/scripts.js');
    ?>
</head>
<body<?php if($this->platform==2):?> class="ios"<?php elseif($this->platform==3):?> class="windows-phone"<?php endif;?>>

<?= $this->renderPartial('//layouts/_header'); ?>
<?= $this->renderPartial('//layouts/_mobile_header'); ?>
<?= $this->renderPartial('//layouts/_platforms'); ?>
<?= $this->renderPartial('//layouts/_sidebar'); ?>
<?= $this->renderPartial('//layouts/_navbar'); ?>
<div class="main">
    <section class="content row">
        <?php
        if(Yii::app()->user->hasFlash('success'))
            echo '<div class=\'alert alert-success rtl fade in\'>
                    <button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>
                    '.Yii::app()->user->getFlash('success').'
                </div>';
        else if(Yii::app()->user->hasFlash('failed'))
            echo '<div class=\'alert alert-danger rtl fade in\'>
                    <button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>
                    '.Yii::app()->user->getFlash('failed').'
                </div>';
        ?>
        <?php echo $content; ?>
        <?= $this->renderPartial('//layouts/_footer'); ?>
    </section>
</div>
</body>
</html>
<?php //Yii::app()->clientScript->registerScript('set-height-to-main', "
//    if($('.main').height() < $(window).height())
//        $('.main').height($(window).height()-130);
//", CClientScript::POS_LOAD);?>