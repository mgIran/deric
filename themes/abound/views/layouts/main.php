<?php
/** @var $this Controller */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $this->siteName . (!empty($this->pageTitle)?' - ' . $this->pageTitle:'') ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="<?= strip_tags($this->description) ?>">
    <meta name="author" content="Rahbod Developing Software Co">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');
    ?>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/css/fontiran.css">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
    $cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
    $cs->registerCssFile($baseUrl . '/css/font-awesome.css');
    $cs->registerCssFile($baseUrl . '/css/ionicons.css');
    $cs->registerCssFile($baseUrl . '/css/AdminLTE.css');
    $cs->registerCssFile($baseUrl . '/plugins/iCheck/all.css');
    $cs->registerCssFile($baseUrl . '/css/skins/skin-blue.min.css');
    $cs->registerCssFile($baseUrl . '/css/bootstrap-rtl.min.css');
    $cs->registerCssFile($baseUrl . '/css/rtl.css');

    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('jquery.ui');
    $cs->registerScriptFile($baseUrl . '/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl . '/plugins/iCheck/icheck.min.js');
    $cs->registerScriptFile($baseUrl . '/js/app.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/js/script.js');
    $cs->registerScript('icheck','
//        $(\'input\').iCheck({
//          checkboxClass: \'icheckbox_square-blue\',
//          radioClass: \'iradio_square-blue\',
//          increaseArea: \'20%\' // optional
//        });
    ',CClientScript::POS_READY);
    ?>
</head>

<body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <?php require_once '_header.php'; ?>
        <?php require_once '_sidebar.php'; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $this->pageHeader ?>
                    <small><?php echo $this->pageDescription ?></small>
                </h1>
                <?php if(isset($this->breadcrumbs)):?>
                    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>$this->breadcrumbs,
                        'homeLink'=>false,
                        'htmlOptions'=>array('class'=>'breadcrumb')
                    )); ?><!-- breadcrumbs -->
                <?php endif?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php echo $content; ?>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

<!-- Require the footer -->
<?php require_once('_footer.php') ?>

</body>
</html>