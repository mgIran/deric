<?
/* @var $this SiteController */
/* @var $newestProgramDataProvider CActiveDataProvider */
/* @var $newestGameDataProvider CActiveDataProvider */
/* @var $newestEducationDataProvider CActiveDataProvider */
/* @var $suggestedDataProvider CActiveDataProvider */
/* @var $specialAdvertise SpecialAdvertises */
/* @var $advertise CActiveDataProvider */
/* @var $topProgramDataProvider CActiveDataProvider */
/* @var $bestsellingProgramDataProvider CActiveDataProvider */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.theme.default.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
?>

    <div class="app-box">
        <div class="top-box">
            <div class="title pull-right">
                <h2>جدیدترین برنامه ها</h2>
            </div>
            <a class="pull-left btn btn-success more-app" href="<?php echo $this->createUrl('/apps/programs');?>">بیشتر</a>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$newestProgramDataProvider,
            'id'=>'newest-programs',
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>
    <div class="app-box">
        <div class="top-box">
            <div class="title pull-right">
                <h2>جدیدترین بازی ها</h2>
            </div>
            <a class="pull-left btn btn-success more-app" href="<?php echo $this->createUrl('/apps/games');?>">بیشتر</a>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'id'=>'newest-games',
            'dataProvider'=>$newestGameDataProvider,
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>
    <?php if($advertise->totalItemCount):?>
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$advertise,
            'id'=>'advertises',
            'itemView'=>'_advertise_item',
            'template'=>'{items}',
            'itemsCssClass'=>'advertise-carousel'
        ));?>
    <?php endif;?>
    <div class="app-box">
        <div class="top-box">
            <div class="title pull-right">
                <h2>برترین ها</h2>
            </div>
            <a class="pull-left btn btn-success more-app" href="<?php echo $this->createUrl('/apps/top');?>">بیشتر</a>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$topProgramDataProvider,
            'id'=>'top-programs',
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>
    <div class="app-box">
        <div class="top-box">
            <div class="title pull-right">
                <h2>پرفروش ترین ها</h2>
            </div>
            <a class="pull-left btn btn-success more-app" href="<?php echo $this->createUrl('/apps/bestselling');?>">بیشتر</a>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$bestsellingProgramDataProvider,
            'id'=>'bestselling-programs',
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>

<?php if($specialAdvertise) {?>
    <div class="banner-box">
        <div class="banner-carousel">
            <div class="banner-item">
                <a class="absolute-link" href="<?php echo $this->createUrl('/apps/'.CHtml::encode($specialAdvertise->app->id).'/'.CHtml::encode($specialAdvertise->app->lastPackage->package_name));?>"></a>
                <div class="fade-overly"></div>
                <?
                Yii::app()->clientScript->registerCss('fade-overly', "
                    .content .banner-box .banner-carousel .banner-item{
                        background-color: {$specialAdvertise->fade_color};
                    }
                    .content .banner-box .banner-carousel .banner-item .fade-overly{
                        background: -moz-linear-gradient(left,{$specialAdvertise->fade_color} 0%, rgba(0,0,0,0) 100%);
                        background: -webkit-linear-gradient(left, {$specialAdvertise->fade_color} 0%, rgba(0,0,0,0) 100%);
                        background: -o-linear-gradient(left, {$specialAdvertise->fade_color} 0%, rgba(0,0,0,0) 100%);
                        background: -ms-linear-gradient(left, {$specialAdvertise->fade_color} 0%, rgba(0,0,0,0) 100%);
                        background: linear-gradient(to right, {$specialAdvertise->fade_color} 0%, rgba(0,0,0,0) 100%);
                    }
                ");
                ?>
                <?= $this->renderPartial('/apps/_vertical_app_item', array('data' => $specialAdvertise->app)) ?>
                <?
                if($specialAdvertise->cover && file_exists(Yii::getPathOfAlias('webroot').'/uploads/advertisesCover/'.$specialAdvertise->cover)) {
                    ?>
                    <img src="<?= $this->createAbsoluteUrl('/uploads/advertisesCover/'.$specialAdvertise->cover) ?>">
                    <?
                }
                ?>
            </div>
        </div>
    </div>
<?php }?>
    <div class="app-box">
        <div class="top-box">
            <div class="title pull-right">
                <h2>تازه های آموزشی</h2>
            </div>
            <a class="pull-left btn btn-success more-app" href="<?php echo $this->createUrl('/apps/educations');?>">بیشتر</a>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'id'=>'newest-educations',
            'dataProvider'=>$newestEducationDataProvider,
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>
    <div class="app-box suggested-list">
        <div class="top-box">
            <div class="title pull-right">
                <h2>پیشنهاد ما به شما</h2>
            </div>
        </div>
        <?php $this->widget('zii.widgets.CListView', array(
            'id'=>'newest-educations',
            'dataProvider'=>$suggestedDataProvider,
            'itemView'=>'_app_item',
            'template'=>'{items}',
            'itemsCssClass'=>'app-carousel'
        ));?>
    </div>
<?
Yii::app()->clientScript->registerScript('carousels','
    var owl = $(".app-carousel");
    owl.owlCarousel({
        responsive:{
            0:{
                items : 3,
            },
            410:{
                items : 3,
            },
            580:{
                items : 3
            },
            800:{
                items : 4
            },
            1130:{
                items : 5
            },
            1370:{
                items : 6
            }
        },
        lazyLoad :true,
        margin :0,
        rtl:true,
        nav:true,
        dots:false,
        navText : ["","<span class=\'icon-chevron-left\'></span>"]
    });

    $(".advertise-carousel").owlCarousel({
        responsive:{
            0:{
                items : 1,
            },
            410:{
                items : 2,
            },
            580:{
                items : 4
            },
            800:{
                items : 4
            },
            1130:{
                items : 4
            },
            1370:{
                items : 4
            }
        },
        lazyLoad :true,
        margin :0,
        rtl:true,
        nav:false,
        dots:true,
        loop:true,
        autoplay:true
    });
'
);