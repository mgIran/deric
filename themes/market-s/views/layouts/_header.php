<div class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 navbar-header">
                <a href="#" class="navbar-brand sisen-brand">
                    <img src="<?= Yii::app()->createAbsoluteUrl('themes/market/images/logo.png'); ?>">
                    <h1 class="hidden"><?= $this->siteName ?></h1>
                    <h2 class="hidden"><?= $this->pageTitle ?></h2>
                    <h5 class="hidden-sm"><?= $this->siteName ?></h5>
                    <h5 class="visible-sm"><?= $this->pageTitle ?></h5>
                </a>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 icons">
                <div class="icon-android">
                    <a class="link-android" href="<?php echo $this->createUrl('/android');?>">
                        <span class="android"></span>
                        <h5>اندروید</h5>
                    </a>
                </div>
                <div class="icon-ios">
                    <a class="link-ios" href="<?php echo $this->createUrl('/ios');?>">
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
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 guide">
                <div class="guide-user">
                    <div class="downloader">
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
                    <a class="user" href="#">
                        <span class="glyphicon user-gi"></span>
                    </a>
                </div>
                <?php
                if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
                ?>
                    <div class="user-section">

                        <div class="avatar">
                            <span class="icon icon-user"></span>
                            <div class="tri-1"></div>
                            <div class="tri-2"></div>
                        </div>
                        <div class="user-menu">
                            <div class="inner">
                                <div class="avatar">
                                    <span class="icon icon-user"></span>
                                </div>
                                <div class="user-detail">
                                    <span class="name"><?= $this->userDetails->getShowName(); ?></span>
                                    <span class="type"><?= $this->userDetails->roleLabels[Yii::app()->user->roles] ?></span>
                                    <span class="type">اعتبار : <?= Controller::parseNumbers(number_format($this->userDetails->credit, 0)) ?> تومان</span>
                                </div>
                                <footer>
                                    <a class="btn btn-default" href="<?= Yii::app()->createUrl('/dashboard') ?>">پنل کاربری</a>
                                    <?
                                    if(Yii::app()->user->roles == 'developer'):
                                        ?>
                                        <a class="btn btn-default" href="<?= Yii::app()->createUrl('/developers/panel') ?>">پنل توسعه دهندگان</a>
                                    <?
                                    endif;
                                    ?>
                                    <a class="btn btn-danger pull-left" href="<?= Yii::app()->createUrl('logout') ?>">خروج</a>
                                </footer>
                            </div>
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
                            <a class="link-svg-search" href="#"><i class="glyphicon svg-search"></i></a>
                        </div>
                    <?
                    $this->endWidget();
                    ?>
                    <button type="button" class="glyphicon close svg-close" data-dismiss="hide-search"><i class="glyphicon"></i></button>
                </div>
            </div>
            <div class="hide-menu">
                <div class="title-menu">
                    <div><a href="<?= $this->createUrl('/apps/games') ?>"><span>بازی ها</span></a></div>
                    <div><a href="<?= $this->createUrl('/apps/programs') ?>"><span>برنامه ها</span></a></div>
                </div>
                <div class="list-menu">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 right-list">
                            <ul>
                                <?php foreach($this->categories['games'] as $category):?>
                                    <li><a href="<?php echo Yii::app()->createUrl('/apps/games/'.$category->id.'/'.urlencode($category->title));?>"><?php echo $category->title;?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <div class="border"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 left-list">
                            <ul class="nice">
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

<!--<div class="notification--><?php //echo (count($this->userNotifications)==0)?null:' active';?><!--">-->
<!--    <a href="--><?php //echo $this->createUrl('/users/public/notifications');?><!--" class="icon icon-bell">-->
<!--        --><?php //if(count($this->userNotifications)!=0):?>
<!--            <span class="lbl">--><?php //echo count($this->userNotifications);?><!--</span>-->
<!--        --><?php //endif;?>
<!--    </a>-->
<!--</div-->