<?php
/** @var $this Controller */
?>
<div class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 navbar-header">
                <a href="<?= Yii::app()->getBaseUrl(true)?>" class="navbar-brand sisen-brand">
                    <img src="<?= Yii::app()->createAbsoluteUrl('themes/market/images/logo.png'); ?>">
                    <h1 class="hidden"><?= $this->siteName ?></h1>
                    <h2 class="hidden"><?= $this->pageTitle ?></h2>
                    <h5 class="hidden-sm hidden-xs"><?= $this->siteName ?></h5>
                    <h5 class="visible-sm"><?= $this->pageTitle ?></h5>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs icons">
                <div class="table">
                    <div class="icon-android">
                        <a class="link-android <?= $this->platform == 1?'active':'' ?>" href="<?php echo $this->createUrl('/android');?>">
                            <span class="android"></span>
                            <h5>اندروید</h5>
                        </a>
                    </div>
                    <div class="icon-ios">
                        <a class="link-ios <?= $this->platform == 2?'active':'' ?>" href="<?php echo $this->createUrl('/ios');?>">
                            <span class="ios"></span>
                            <h5>آی او اس</h5>
                        </a>
                    </div>
                    <div class="lists">
                        <a class="link-lists" href="#">
                            <span class="glyphicon category"></span>
                            <h5>دسته بندی ها</h5>
                            <span class="glyphicon arrows-down hidden-xs"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-7 guide">
                <div class="guide-user">
                    <div class="downloader hidden-xs">
                        <div class="download">
                            <a class="download-sisen" href="<?php echo $this->createUrl('/site/underConstruction');?>">
                                <h5>دانلود سیسن اپ</h5>
                                <img src="<?php echo Yii::app()->theme->baseUrl.'/images/app-dl-logo.png'?>">
                            </a>
                        </div>
                    </div>
                    <a class="search-user" href="#">
                        <span class="glyphicon search"></span>
                    </a>
                    <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'): ?>
                    <a class="notification-icon hidden-xs" href="<?= Yii::app()->createUrl('/users/public/notifications') ?>">
                        <span class="icon icon-bell"></span>
                    </a>
                    <?php endif; ?>
                    <a class="user hidden-xs" href="#">
                        <span class="glyphicon user-gi"></span>
                    </a>
                    <div class="mobail-bar hidden-lg hidden-md hidden-sm">
                        <a href="#"></a>
                        <span class="glyphicon icon-bar"></span>
                    </div>
                </div>
                <?php
                if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
                    ?>
                    <div class="tri-1"></div>
                    <div class="tri-2"></div>
                    <div class="user-menu">
                        <div class="inner">
                            <div class="avatar">
                                <?php if (Yii::app()->user->avatar && is_file(Yii::getPathOfAlias('webroot').'/uploads/users/avatar/'.Yii::app()->user->avatar)): echo CHtml::image(Yii::app()->getBaseUrl(true) . '/uploads/users/avatar/' . Yii::app()->user->avatar); else: echo '<span class="icon icon-user"></span>'; endif; ?>
                            </div>
                            <div class="user-detail">
                                <div class="name"><?= $this->userDetails->getShowName(); ?></div>
                                <div class="type"><i class="icon icon-user"></i><?= $this->userDetails->roleLabels[Yii::app()->user->roles] ?></div>
                                <div class="type"><i class="glyphicon coins"></i>اعتبار : <?= Controller::parseNumbers(number_format($this->userDetails->credit, 0)) ?> تومان</div>
                            </div>
                            <footer>
                                <a class="btn btn-default" href="<?= Yii::app()->createUrl('/dashboard') ?>">پنل
                                    کاربری</a>
                                <?php if (Yii::app()->user->roles == 'developer'): ?>
                                    <a class="btn btn-default"
                                       href="<?= Yii::app()->createUrl('/developers/panel') ?>">پنل توسعه
                                        دهندگان</a>
                                <?php endif; ?>
                                <a class="btn btn-default pull-left" href="<?= Yii::app()->createUrl('logout') ?>">خروج</a>
                            </footer>
                        </div>
                    </div>
                <?php
                else:
                ?>
                    <div class="login-process">
                        <div class="login"><a href="<?= Yii::app()->createUrl('/login') ?>">ورود</a></div>
                        <div class="border"></div>
                        <div class="register"><a href="<?= Yii::app()->createUrl('/register') ?>">ثبت نام</a></div>
                    </div>
                <?php
                endif;
                ?>
                <div class="hide-search">
                    <?
                    $form = $this->beginWidget('CActiveForm',array(
                        'id' => 'header-serach-form',
                        'action' => array('/apps/search'),
                        'method' => 'get',
                        'htmlOptions' => array(
                            'class' => 'form-search'
                        )
                    ));
                    ?>
                        <div class="form-group">
                            <?= CHtml::textField('term',isset($_GET['term'])?trim($_GET['term']):'',array('class' => 'text-search','placeholder' => 'جستجو کنید ...')); ?>
                            <button class="link-svg-search" type="submit"><i class="glyphicon svg-search"></i></button>
                        </div>
                    <?
                    $this->endWidget();
                    ?>
                    <button type="button" class="glyphicon close svg-close" data-dismiss="hide-search"><i class="glyphicon"></i></button>
                </div>
            </div>

            <div class="mobail-menu-hide hidden-lg hidden-md hidden-sm relative">
                <ul>
                    <li>منوی کاربری</li>
                    <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'): ?>
                        <li><a href="<?php echo $this->createUrl('/dashboard') ?>"></a><span class="glyphicon dashboard-icon"></span><span class="text">پنل کاربری</span></li>
                        <?php if(Yii::app()->user->roles == 'developer'):?>
                            <li><a href="<?php echo $this->createUrl('/developers/panel');?>"></a><span class="glyphicon developer-icon"></span><span class="text text-danger">پنل توسعه دهندگان</span></li>
                        <?php endif;?>
                        <li><a href="<?php echo $this->createUrl('/logout');?>"></a><span class="glyphicon log-out-icon"></span><span class="text text-danger">خروج از حساب</span></li>
                    <?php else: ?>
                        <li><a href="<?php echo $this->createUrl('/login') ?>"></a><span class="glyphicon login-icon"></span><span class="text">ورود</span></li>
                        <li><a href="<?php echo $this->createUrl('/register') ?>"></a><span class="glyphicon register-icon"></span><span class="text">ثبت نام</span></li>
                    <?php endif; ?>
                </ul>
                <ul>
                    <li>منوی سایت</li>
                    <li><a href="<?php echo $this->createUrl('/android');?>"></a><span class="glyphicon android"></span><span class="text">اندروید</span></li>
                    <li><a href="<?php echo $this->createUrl('/ios');?>"></a><span class="glyphicon ios"></span><span class="text">آی او اس</span></li>
                    <li class="mobail-cat"><a href="#"></a><span class="glyphicon category"></span><span class="text">دسته بندی ها</span></li>
                    <li><a href="<?php echo $this->createUrl('/site/underConstrction');?>"></a><span class="glyphicon android-2"></span><span class="text">دانلود سیسن اپ</span></li>
                </ul>
            </div>
            <div class="bg-menu hidden-lg hidden-md hidden-sm"></div>
            <div class="hide-menu">
                <div class="title-menu">
                    <div><a href="<?= $this->createUrl('/apps/games') ?>"><span>بازی ها</span></a></div>
                    <div><a href="<?= $this->createUrl('/apps/programs') ?>"><span>برنامه ها</span></a></div>
                </div>
                <div class="list-menu">
                    <div class="close-icon visible-xs">
                        <span class="glyphicon icon-close"></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 right-list nicescroll" data-cursorcolor="#fff" data-cursorborder="none"
                             data-railpadding='js:{"top":5,"right":5,"bottom":0,"left":5}' data-autohidemode="leave">
                            <ul class="list-unstyled">
                                <?php foreach($this->categories['games'] as $category):?>
                                    <li><a href="<?php echo Yii::app()->createUrl('/apps/games/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <div class="border"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 left-list nicescroll" data-cursorcolor="#fff" data-cursorborder="none"
                             data-railpadding='js:{"top":5,"right":5,"bottom":0,"left":5}' data-autohidemode="leave">
                            <ul class="list-unstyled">
                                <?php foreach($this->categories['programs'] as $category):?>
                                    <li><a href="<?php echo Yii::app()->createUrl('/apps/programs/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
