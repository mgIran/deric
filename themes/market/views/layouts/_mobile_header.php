<header class="mobile hidden-lg hidden-md col-sm-12 col-xs-12">
    <div class="side-bar-trigger hidden-lg hidden-md hidden-sm"></div>
    <div class="logo-box pull-right">
        <a href="<?= Yii::app()->createAbsoluteUrl('//'); ?>"></a>
        <h1><?= $this->siteName ?></h1>
        <h2><?= $this->pageTitle ?></h2>
        <img class="logo" src="<?= Yii::app()->createAbsoluteUrl('themes/market/images/rahbod.svg'); ?>" alt="Rahbod" >
    </div>
    <ul class="mobile-nav nav">
        <li class="navbar-trigger">
            <a href="#"></a>
            <span class="svg svg-bars"></span>
        </li>
        <li class="search-trigger">
            <a href="#"></a>
            <span class="svg svg-search"></span>
        </li>
    </ul>
    <div class="mobile-search col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <span class="svg svg-close search-trigger"></span>
        <?
        $form = $this->beginWidget('CActiveForm',array(
            'id' => 'mobile-search-form',
            'action' => array('/site/search'),
        ));
        ?>
        <div class="form-group">
            <?= CHtml::textField('searchText','',array('placeholder' => 'جستجو کنید ...')); ?>
            <span class="add-in svg svg-search"></span>
        </div>
        <?
        $this->endWidget();
        ?>
    </div>
</header>

<!-- mobile nav bar -->
<nav class="mobile-navbar navbar navbar-default hidden-lg hidden-md">
    <div class="navbar-header">
        <?
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user') {
            ?>
            <div class="avatar">
                <span class="icon icon-user"></span>
            </div>
            <div class="user-detail">
                <span class="name"><?= $this->userDetails->getShowName(); ?></span>
                <span class="type"><?= $this->userDetails->roleLabels[Yii::app()->user->roles] ?></span>
                <span class="type">اعتبار : <?= Controller::parseNumbers($this->userDetails->credit) ?> تومان</span>
            </div>
            <div class="navbar-links">
                <a class="btn btn-default" href="<?= Yii::app()->createUrl('/dashboard') ?>">پنل کاربری</a>
                <?
                if(Yii::app()->user->roles == 'developer'):
                    ?>
                    <a class="btn btn-default" href="<?= Yii::app()->createUrl('/developers/panel') ?>">
                        پنل توسعه دهندگان
                    </a>
                    <?
                endif;
                ?>
                <a class="btn btn-danger pull-left" href="<?= Yii::app()->createUrl('logout') ?>">خروج</a>
            </div>
            <?
        }else
        {
            ?>
            <div class="header-links">
                <a href="<?= Yii::app()->createUrl('/login') ?>">
                    ورود
                </a>
                <a href="<?= Yii::app()->createUrl('/register') ?>">
                    ثبت نام
                </a>
            </div>
            <?
        }
        ?>
    </div>
    <ul class="nav navbar-nav">
        <li>
            <a href="#" data-toggle="collapse" data-target="#mobile-categories">دسته ها&nbsp;&nbsp;<span class="icon-chevron-down"></span></a>
        </li>
        <div class="panel panel-body panel-group collapse" id="mobile-categories">
            <div id="category-collapse-parent">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <a data-toggle="collapse" data-parent="#category-collapse-parent" data-target="#m-app-cat" href="#" class="cat-menu-head">برنامه ها</a>
                        <ul class="cat-menu collapse" id="m-app-cat">
                            <li><a href="<?php echo Yii::app()->createUrl('/apps/programs');?>">همه برنامه ها</a></li>
                            <?php foreach($this->categories['programs'] as $category):?>
                                <li><a href="<?php echo Yii::app()->createUrl('/apps/programs/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <a data-toggle="collapse" data-target="#m-games-cat" data-parent="#category-collapse-parent" href="#" class="cat-menu-head">بازی ها</a>
                        <ul class="cat-menu collapse" id="m-games-cat">
                            <li><a href="<?php echo Yii::app()->createUrl('/apps/games');?>">همه بازی ها</a></li>
                            <?php foreach($this->categories['games'] as $category):?>
                                <li><a href="<?php echo Yii::app()->createUrl('/apps/games/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <a data-toggle="collapse" data-target="#m-edu-cat" data-parent="#category-collapse-parent" href="#" class="cat-menu-head">آموزش ها</a>
                        <ul class="cat-menu collapse" id="m-edu-cat">
                            <li><a href="<?php echo Yii::app()->createUrl('/apps/educations');?>">همه آموزش ها</a></li>
                            <?php foreach($this->categories['educations'] as $category):?>
                                <li><a href="<?php echo Yii::app()->createUrl('/apps/educations/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <li><a href="<?= Yii::app()->user->hasState('platformName')?Yii::app()->baseUrl.'/'.Yii::app()->user->getState('platformName'):Yii::app()->createAbsoluteUrl('//') ?>">خانه</a></li>
        <li><a href="<?= $this->createUrl('/apps/discount') ?>">تخفیفات</a></li>
        <li>
            <a href="<?php echo $this->createUrl('/site/underConstruction');?>">
                <span class="icon-download-alt"></span>
                هایپر اپس را دانلود کنید
            </a>
        </li>
    </ul>
</nav>
<div class="overlay fade"></div>