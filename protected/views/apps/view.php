<?php
/* @var $this AppsController */
/* @var $model Apps */
/* @var $similar CActiveDataProvider */
/* @var $bookmarked boolean */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.theme.default.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.magnific-popup.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/magnific-popup.css');

if($model->platform) {
    $platform = $model->platform;
    $filesFolder = $platform->name;
    $filePath = Yii::getPathOfAlias("webroot") . "/uploads/apps/files/{$filesFolder}/";
}
if(!$model->lastPackage)
    throw new CHttpException(404, "نرم افزار موردنظر بسته تایید شده ندارد.");

$rating = $model->calculateRating();
?>
<div class="media-game">
    <div class="media-game-to">
        <div class="media">
            <div class="media-right img-media">
                <img src="<?= Yii::app()->createUrl('/uploads/apps/icons/'.$model->icon);?>" alt="<?= $model->title ?>" class="media-object">
            </div>
            <div class="media-body media-b">
                <h4 class="media-heading title-m"><b><?= $model->title ?></b></h4>
                <div class="text-media"><a href="<?php echo $this->createUrl('apps/developer?title='.($model->developer?urlencode($model->developer->userDetails->developer_id).'&id='.$model->developer_id:urlencode($model->developer_team).'&t=1'));?>"><i class="glyphicon coding"></i><span><?= $model->getDeveloperName() ?></span></a></div>
                <div class="text-media"><a href="<?php echo $this->createUrl("/{$model->platform->name}"); ?>"><i class="glyphicon android"></i><span><?= $model->platform->title ?></span></a></div>
                <div class="text-media"><i class="glyphicon download"></i><span><?= Controller::parseNumbers($model->install) ?>&nbsp;نصب فعال</span></div>
                <div class="text-media">
                    <i class="glyphicon coins"></i>
                    <?
                    if($model->hasDiscount()):
                        ?>
                        <span class="text-danger text-line-through"><?= Controller::parseNumbers(number_format($model->price, 0)).' تومان'; ?></span>
                        <span ><?= Controller::parseNumbers(number_format($model->offPrice, 0)).' تومان' ; ?></span>
                    <?
                    else:
                        ?>
                        <span><?= $model->price?Controller::parseNumbers(number_format($model->price, 0)).' تومان':'رایگان'; ?></span>
                    <?
                    endif;
                    ?>
                </div>
                <div class="text-media"><div class="star"><?= Controller::printRateStars($model->rate) ?></div><span>(<?= Controller::parseNumbers(number_format($rating['totalAvg'])) ?> رای)</span></div>
            </div>
            <div class="media-body media-b2">
                <div class="empty hidden-xs"></div>
                <div class="text-media">
                        <i class="glyphicon category"></i><span>دسته:
                        <a href="<?php echo $model->category->getViewUrl() ?>">
                            <?= $model->category?$model->category->title:'' ?>
                        </a>
                    </span>
                </div>
                <div class="text-media"><i class="glyphicon file-size"></i><span>حجم : 20 مگابایت</span></div>
                <div class="text-media"><i class="glyphicon version"></i><span>نسخه: <?= $model->lastPackage->version ?></span></div>
            </div>
            <div class="media-dn">
                <div class="btn downloady">
                    <a class="hidden-sm hidden-xs" href="#" data-toggle="modal" data-target="#install-modal">نصب</a>
                    <?php if($model->price>0):?>
                        <a class="hidden-md hidden-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/apps/buy/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title)));?>">نصب</a>
                    <?php else:?>
                        <a class="hidden-md hidden-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/apps/download/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title)));?>">نصب</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<?
if($model->multimedia):
    ?>
    <div class="game-images">
        <h5><b>تصاویر بازی</b></h5>
        <div class="father-scroll nicscroll" data-cursorcolor="#00381d" data-cursorborder="none"
             data-railpadding='js:{"top":5,"right":5,"bottom":0,"left":5}' data-autohidemode="leave">
            <div class="game-images-to">
                <?
                $imager = new Imager();
                foreach ($model->multimedia as $key => $image):
                    if ($image->type == AppImages::TYPE_IMAGE &&
                        file_exists(Yii::getPathOfAlias("webroot") . '/uploads/apps/images/' . $image->image)):
                        $imageInfo = $imager->getImageInfo(Yii::getPathOfAlias("webroot") . '/uploads/apps/images/' . $image->image);
                        $ratio = $imageInfo['width'] / $imageInfo['height'];
                        ?>

                        <div class="img-scroll" style="width: <?php echo ceil(318 * $ratio); ?>px;"
                             data-toggle="modal" data-index="<?= $key ?>" data-target="#carousesl-modal">
<!--                            <a href="--><?//= Yii::app()->createAbsoluteUrl('/uploads/apps/images/' . $image->image) ?><!--">-->
                                <img
                                src="<?= Yii::app()->createAbsoluteUrl('/uploads/apps/images/' . $image->image) ?>"
                                alt="<?= $model->title ?>">
<!--                            </a>-->
                        </div>
                    <?
                    else:
                        ?>
                        <div class="img-scroll"
                             data-toggle="modal" data-index="<?= $key ?>" data-target="#carousesl-modal">
                            <?= $image->iframe ?>
                        </div>
                    <?
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    </div>
<? endif; ?>
<?php
if(!empty($model->description)):
?>
<div class="text-p">
    <h5><b>توضیحات</b></h5>
    <p class="text-right"><?php
        $purifier  = new CHtmlPurifier();
        $purifier->setOptions(array(
            'HTML.Allowed'=> 'p,b,i,br,img,span',
            'HTML.AllowedAttributes'=> 'style,id,class,src,a.href,dir',
        ));
        echo $purifier->purify($model->description);
        ?></p>
</div>
<?php
endif;
?>
<div class="changes">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 last-change">
            <?php if($model->change_log || !empty($model->change_log)):?>
                <h5><b>آخرین تغییرات</b></h5>
                <?php echo $purifier->purify($model->change_log) ?>
            <?php endif; ?>

            <!--Comments-->
            <? $this->widget('comments.widgets.ECommentsListWidget', array('model' => $model)); ?>
            <!--End Comments-->
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 access">
            <?php
            $model->permissions = CJSON::decode($model->permissions);
            if($model->permissions):?>
                <h5><b>دسترسی ها</b></h5>
                <ul>
                    <?
                    foreach($model->permissions as $permission):
                        echo "<li>{$permission}</li>";
                    endforeach;
                    ?>
                </ul>
            <?php endif;?>
            <?php
            $this->renderPartial('_rating',array(
                'model' => $model,
                'rating' => $rating,
            ));
            ?>
            <?php
            Yii::import('advertises.models.*');
            $criteria=AppAdvertises::InAppQuery($this->platform);
            $criteria->addCondition('(app_id IS NULL OR app_id <> :id)');
            $criteria->params[':id'] = $model->id;
            $inAppAdvertises = AppAdvertises::model()->findAll($criteria);
            if($inAppAdvertises):
                $data = count($inAppAdvertises)>1?$inAppAdvertises[array_rand($inAppAdvertises)]:$inAppAdvertises[0];
                $detail = $data->getShowDetail();
            ?>
                <div class="baner baner-two">
                    <div class="baner-box">
                        <img class="baner-full" src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$data->cover ?>"
                             alt="<?= CHtml::encode($data->title) ?>">
                        <div class="baner-box-to">
                            <div class="baner-item">
                                <div class="media">
                                    <div class="media-right photo">
                                        <div class="media-right-inner">
                                            <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$data->cover ?>"
                                                 alt="<?= CHtml::encode($data->title) ?>"
                                                 class="media-object media-img">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="media-heading"><a href="<?= $detail->url ?>"><?= $data->title ?></a></h5>
                                        <div class="btn downloady"><a href="<?= $detail->downloadUrl ?>">دانلود</a></div>
                                        <p class="text-justify texts"><?= $detail->summary ?></p>
                                        <div class="votes">
                                            <div class="free"><?= Controller::parseNumbers($detail->price) ?></div>
                                            <div class="star"><?= Controller::printRateStars(doubleval($detail->rate)) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="details">
                                <div class="det-box">
                                    <span class="glyphicon d-load"></span><span class="text-box"><?= Controller::parseNumbers(number_format($detail->download)) ?> دانلود</span>
                                </div>
                                <div class="det-box hidden-xs hidden-sm hidden-md">
                                    <span class="glyphicon a-roid"></span><span class="text-box"><?= $data->platform->title ?></span>
                                    </div>
                                <div class="det-box hidden-xs hidden-sm">
                                    <span class="glyphicon v-lume"></span><span class="text-box">حجم:<?= Controller::parseNumbers($detail->size) ?></span>
                                </div>
                                <div class="det-box">
                                    <a href="<?php if($data->app_id && $data->app)
                                        echo $this->createUrl('apps/developer?title='.($data->app->developer?urlencode($data->app->developer->userDetails->developer_id).'&id='.$data->app->developer_id:urlencode($data->app->developer_team).'&t=1'));
                                    ?>">
                                        <span class="glyphicon d-loper"></span><?= $detail->developer ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
</div>
<?php
if($similar):
?>
<section>
    <div class="see">
        <div class="row">
            <div class="see-all col-lg-12 col-md-12 col-sm-12 col-xs-12">
<!--                <a class="link-grid" href="#"><span>مشاهده همه</span><span class="grid"></span></a>-->
                <a class="novelty" href="#">موارد مشابه</a>
            </div>
        </div>
    </div>
    <div class="game">
        <div class="imgs">
            <div id="demo2" class="is-carousel"  data-items="8" data-loop="1" data-dots="0" data-nav="1" data-mouse-drag="1" data-responsive='{"1200":{"items":"8"},"992":{"items":"7"},"768":{"items":"4"},"650":{"items":"3"},"0":{"items":"1"}}'>
                <?php foreach ($similar as $item):?>
                   <?php $this->renderPartial('//site/_app_item', ['data' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php
endif;
?>



<? /*
<div class="app col-sm-12 col-xs-12">
    <div class="app-inner">
        <div class="pic">
            <img src="<?= Yii::app()->createUrl('/uploads/apps/icons/'.$model->icon);?>" alt="<?= $model->title ?>">
        </div>
        <div class="app-heading">
            <h2><?= $model->title ?></h2>
            <div class="row-fluid">
                <span>پلتفرم: <a href="<?php echo $this->createUrl("/{$model->platform->name}"); ?>"><?= $model->platform->title ?></a></span>
                <span class="app-rate pull-left">
                    <?= Controller::printRateStars($model->rate) ?>
                </span>
            </div>
            <div class="row-fluid">
                <span><a href="<?php echo $this->createUrl('apps/developer?title='.($model->developer?urlencode($model->developer->userDetails->developer_id).'&id='.$model->developer_id:urlencode($model->developer_team).'&t=1'));?>"><?= $model->getDeveloperName(); ?></a></span>
                <span><a href="<?php echo $this->createUrl('apps/'.((strpos($model->category->path,'2-')!==false)?'games':'programs').'/'.$model->category->id.'/'.urlencode($model->category->title));?>"><?= $model->category?$model->category->title:''; ?></a></span>
            </div>
            <div class="row-fluid pr6">
                <svg class="svg svg-bag green"><use xlink:href="#bag"></use></svg>
                <span ><?= Controller::parseNumbers($model->install) ?>&nbsp;نصب فعال</span>
            </div>
            <div class="row-fluid">
                <div class="row-fluid">
                    <svg class="svg svg-coin green"><use xlink:href="#coin"></use></svg>
                    <?
                    if($model->hasDiscount()):
                        ?>
                        <span class="text-danger text-line-through"><?= Controller::parseNumbers(number_format($model->price, 0)).' تومان'; ?></span>
                        <span ><?= Controller::parseNumbers(number_format($model->offPrice, 0)).' تومان' ; ?></span>
                    <?
                    else:
                        ?>
                        <span ><?= $model->price?Controller::parseNumbers(number_format($model->price, 0)).' تومان':'رایگان'; ?></span>
                    <?
                    endif;
                    ?>
                </div>
                <div class="row-fluid">
                <span class="pull-left">
                    <button class="btn btn-success btn-install hidden-sm hidden-xs" type="button" data-toggle="modal" data-target="#install-modal">نصب</button>
                    <?php if($model->price>0):?>
                        <a class="btn btn-success btn-install hidden-md hidden-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/apps/buy/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title)));?>">نصب</a>
                    <?php else:?>
                        <a class="btn btn-success btn-install hidden-md hidden-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/apps/download/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title)));?>">نصب</a>
                    <?php endif;?>
                </span>
                    <?php if(!Yii::app()->user->isGuest):?>
                        <span class="pull-left relative bookmark<?php echo ($bookmarked)?' bookmarked':'';?>">
                        <?= CHtml::ajaxLink('',array('/apps/bookmark'),array(
                            'data' => "js:{appId:$model->id}",
                            'type' => 'POST',
                            'dataType' => 'JSON',
                            'success' => 'js:function(data){
                                if(data.status){
                                    if($(".bookmark").hasClass("bookmarked")){
                                        $(".svg-bookmark#bookmark").html("<use xlink:href=\'#add-to-bookmark\'></use>");
                                        $(".bookmark").removeClass("bookmarked");
                                        $(".bookmark").find(".title").text("نشان کردن");
                                    }
                                    else{
                                        $(".svg-bookmark#bookmark").html("<use xlink:href=\'#bookmarked\'></use>");
                                        $(".bookmark").find(".title").text("نشان شده");
                                        $(".bookmark").addClass("bookmarked");
                                    }
                                }
                                else
                                    alert("در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.");
                                return false;
                            }'
                        ),array(
                            'id' =>"bookmark-app"
                        )); ?>
                            <svg id="bookmark" class="svg svg-bookmark green"><use xlink:href="<?php echo ($bookmarked)?'#bookmarked':'#add-to-bookmark';?>"></use></svg>
                        <svg id="remove" class="svg svg-bookmark green"><use xlink:href="#remove-bookmark"></use></svg>
                        <script>
                            $(function(){
                                $(this).find(".svg-bookmark#remove").hide();
                                $('body').on('mouseenter','.bookmark',function(){
                                    if($(this).hasClass('bookmarked')) {
                                        $(this).find(".svg-bookmark#bookmark").hide();
                                        $(this).find(".svg-bookmark#remove").show();
                                    }
                                });
                                $('body').on('mouseleave','.bookmark',function(){
                                    $(this).find(".svg-bookmark#bookmark").show();
                                    $(this).find(".svg-bookmark#remove").hide();
                                });
                            });
                        </script>
                        <span class="green title" ><?php echo ($bookmarked)?'نشان شده':'نشان کردن';?></span>
                    </span>
                    <?php endif;
                    ?>
                </div>
            </div>
            <div class="app-body">
                <?
                if($model->multimedia) {
                    ?>
                    <div class="images-carousel">
                        <?
                        $imager = new Imager();
                        foreach($model->multimedia as $key => $image):
                            if($image->type == AppImages::TYPE_IMAGE &&
                                file_exists(Yii::getPathOfAlias("webroot").'/uploads/apps/images/'.$image->image)):
                                $imageInfo = $imager->getImageInfo(Yii::getPathOfAlias("webroot").'/uploads/apps/images/'.$image->image);
                                $ratio = $imageInfo['width'] / $imageInfo['height'];
                                ?>
                                <div class="image-item" style="width: <?php echo ceil(318 * $ratio); ?>px;"
                                     data-toggle="modal" data-index="<?= $key ?>" data-target="#carousesl-modal">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('/uploads/apps/images/'.$image->image) ?>"><img
                                                src="<?= Yii::app()->createAbsoluteUrl('/uploads/apps/images/'.$image->image) ?>"
                                                alt="<?= $model->title ?>"></a>
                                </div>
                            <?
                            else:
                                ?>
                                <div class="image-item" data-toggle="modal" data-index="<?= $key ?>" data-target="#carousesl-modal">
                                    <?= $image->iframe ?>
                                </div>
                            <?
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <?
                    Yii::app()->clientScript->registerScript('callImageGallery',"
                    $('.images-carousel').magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        tLoading: 'Loading image #%curr%...',
                        mainClass: 'mfp-img-mobile',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                        },
                        image: {
                            tError: '<a href=\"%url%\">The image #%curr%</a> could not be loaded.',
                            titleSrc: function(item) {
                                return '';
                            }
                        }
                    });
                ");
//                Yii::app()->clientScript->registerScript('app-images-carousel',"
//                    $('.images-carousel').owlCarousel({
//                        autoWidth:true,
//                        margin:10,
//                        rtl:true,
//                        dots:false,
//                        video: true,
//                        items:1
//                    });
//                ");
                }
                ?>
                <section>
                    <div class="app-description">
                        <h4>توضیحات برنامه</h4>
                        <p><?php
                            $purifier  = new CHtmlPurifier();
                            $purifier->setOptions(array(
                                'HTML.Allowed'=> 'p,b,i,br,img,span',
                                'HTML.AllowedAttributes'=> 'style,id,class,src,a.href,dir',
                            ));
                            echo $purifier->purify($model->description);
                            ?></p>
                    </div>
                    <a class="more-text" href="#">
                        <span>توضیحات بیشتر</span>
                    </a>
                </section>
                <?php if($model->change_log || !empty($model->change_log)):?>
                    <div class="change-log">
                        <h4>آخرین تغییرات</h4>
                        <div class="app-description">
                            <?php echo $purifier->purify($model->change_log) ?>
                        </div>
                    </div>
                <?php endif;?>
                <div class="app-details">
                    <h4>اطلاعات برنامه</h4>
                    <?php
                    if($model->lastPackage->file_name):
                        ?>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 detail">
                            <h5>حجم</h5>
                            <span class="ltr" ><?= Controller::fileSize($filePath.$model->lastPackage->file_name) ?></span>
                        </div>
                    <?php
                    else:
                        ?>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 detail">
                            <h5>حجم</h5>
                            <span class="ltr" ><?= $model->lastPackage->download_file_size ?></span>
                        </div>
                    <?php
                    endif;
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 detail">
                        <h5>نسخه</h5>
                        <span class="ltr" ><?= $model->lastPackage->version ?></span>
                    </div>
                </div>
                <?php if(!is_null($model->permissions) or $model->permissions!=''):?>
                    <div class="app-details">
                        <?
                        if($model->permissions):
                            echo '<h4>دسترسی ها</h4>';
                            echo '<ul class="list-unstyled">';
                            $model->permissions = CJSON::decode($model->permissions);
                            foreach($model->permissions as $permission):
                                echo "<li>- {$permission}</li>";
                            endforeach;
                            echo '</ul>';
                        endif;
                        ?>
                    </div>
                <?php endif;?>
                <div class="app-comments border-none">
                    <div id="rate-wrapper">

                    </div>
                    <div id="comments">
                        <?
                        $this->widget('comments.widgets.ECommentsListWidget', array(
                            'model' => $model,
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-like col-sm-12 col-xs-12">
        <div class="app-box">
            <div class="top-box">
                <div class="title pull-right">
                    <h2>مشابه</h2>
                </div>
            </div>
            <div class="app-vertical">
                <?php $this->widget('zii.widgets.CListView', array(
                    'id'=>'similar-apps',
                    'dataProvider'=>$similar,
                    'itemView'=>'_vertical_app_item',
                    'template'=>'{items}',
                ));?>
            </div>
        </div>
    </div>

    <div id="install-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>برای دانلود برنامه کد زیر را اسکن کنید</h3>
                    <div class="qr-code-container">
                        <?php if($model->price>0):?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode(Yii::app()->createAbsoluteUrl('/apps/buy/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title))));?>" />
                        <?php else:?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode(Yii::app()->createAbsoluteUrl('/apps/download/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title))));?>" />
                        <?php endif;?>
                    </div>
                    <?php
                    if($model->price>0) {
                        ?>
                        <a href="<?php echo $this->createUrl('/apps/buy/'.CHtml::encode($model->id).'/'.CHtml::encode($model->title)); ?>"
                           class="btn btn-success btn-buy">خرید</a>
                        <?php
                    }else {
                        ?>
                        <div class="text-center" style="margin-bottom: 15px;">
                            <a href="<?php echo Yii::app()->createAbsoluteUrl('/apps/download/'.CHtml::encode($model->id).'/'.urlencode(CHtml::encode($model->title)));?>">دانلود مستقیم برنامه</a>
                        </div>
                        <a href="#" data-dismiss="modal" class="btn btn-default">بستن</a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div id="carousel-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">

                </div>
            </div>
        </div>
    </div>
    <style>
        .pr6 {
            padding-right: 6px;
        }
    </style> */