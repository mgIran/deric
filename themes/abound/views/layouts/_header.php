<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo Yii::app()->getBaseUrl(true)?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><?php echo mb_substr(Yii::app()->name,0,1, 'utf-8') ?></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo Yii::app()->name;?></b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="bars-icon"></i>
            <span class="sr-only">باز / بستن منو</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo Yii::app()->createUrl('/admins/login/logout')?>">
                        <i class="fa fa-power-off"></i> خروج
                    </a>
                </li>
                <!-- Control Sidebar Toggle Button -->
<!--                <li>-->
<!--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
<!--                </li>-->
            </ul>
        </div>
    </nav>
</header>