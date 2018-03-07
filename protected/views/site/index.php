<?
/* @var $this SiteController */
/* @var $dynamicRows RowsHomepage[] */
/* @var $latestGamesDP CActiveDataProvider */
/* @var $latestProgramsDP CActiveDataProvider */
/* @var $topDP CActiveDataProvider */
/* @var $rows CActiveDataProvider[] */
/* @var $slider Advertises[] */
/* @var $specialAd SpecialAdvertises */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.theme.default.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
$slider= null;
?>

<?php $this->renderPartial('/site/_slider', compact('advertises')) ?>
<?php $this->renderPartial('/site/_quick_access') ?>

<?php foreach ($dynamicRows as $dynamicRow):
    $dp = Apps::model()->findAll($dynamicRow->getConstCriteria(Apps::getValidApps($this->platform)));
    if($dp):?>
        <section>
            <div class="see">
                <div class="row">
                    <div class="see-all col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a class="link-grid" href="#"><span>مشاهده همه</span><span class="grid"></span></a>
                        <a class="novelty" href="#"><?= $dynamicRow->title ?></a>
                    </div>
                </div>
            </div>
            <div class="game">
                <div class="imgs">
                    <div id="demo2" class="is-carousel"  data-items="8" data-loop="1" data-dots="0" data-nav="1" data-mouse-drag="1" data-responsive='{"1200":{"items":"8"},"992":{"items":"7"},"768":{"items":"4"},"650":{"items":"3"},"0":{"items":"1"}}'>
                        <?php foreach ($dp as $item):?>
                        <div class="games-item">
                            <div class="thumb"><a href="<?= $item->getViewUrl() ?>"><img src="<?php echo Yii::app()->getBaseUrl(true).'/uploads/apps/icons/'.CHtml::encode($item->icon);?>" alt="<?= CHtml::encode($item->title) ?>"></a></div>
                            <div class="text">
                                <h5 class="title"><a href="<?= $item->getViewUrl() ?>"><?= CHtml::encode($item->title) ?><span class="paragraph-end"></span></a></h5>
                                <div class="free">
                                    <?php if($item->price==0):?>
                                        <a href="<?php echo Yii::app()->createUrl('/apps/free')?>">رایگان</a>
                                    <?php else:?>
                                        <?
                                        if($item->hasDiscount()):
                                            ?>
                                            <span class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($item->price, 0)).' تومان'; ?></span>
                                            <span ><?= Controller::parseNumbers(number_format($item->offPrice, 0)).' تومان' ; ?></span>
                                        <?
                                        else:
                                            ?>
                                            <span ><?= $item->price?Controller::parseNumbers(number_format($item->price, 0)).' تومان':'رایگان'; ?></span>
                                        <?
                                        endif;
                                        ?>
                                    <?php endif;?>
                                </div>
                                <div class="star">
                                    <?= Controller::printRateStars($item->getRate()) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif;
endforeach;
?>

<?php

?>
<section class="baner">
    <div class="baner-box">
        <div class="baner-box-to">
            <div class="baner-item">
                <div class="media">
                    <div class="media-right photo">
                        <img src="images/baner-small.png" class="media-object media-img">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading"><a href="#">ترن هوایی</a></h5>
                        <div class="btn downloady"><a href="#">دانلود</a></div>
                        <p class="text-justify texts hidden-xs hidden-sm">لورم ایپسوم مپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می‌باشد. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>
                        <p class="text-justify texts visible-xs">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای ...</p>
                        <p class="text-justify texts visible-sm">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می‌باشد.</p>
                        <div class="votes">
                            <div class="free">رایگان</div>
                            <div class="star">
                                <i class="icon"></i>
                                <i class="icon"></i>
                                <i class="icon"></i>
                                <i class="icon"></i>
                                <i class="icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="det-box">
                    <span class="glyphicon d-load"></span><span class="text-box">3333 دانلود</span>
                </div>
                <div class="det-box hidden-xs hidden-sm hidden-md">
                    <span class="glyphicon a-roid"></span><span class="text-box">اندروید</span>
                </div>
                <div class="det-box hidden-xs">
                    <span class="glyphicon v-lume"></span><span class="text-box">حجم:33 مگا بایت</span>
                </div>
                <div class="det-box">
                    <a href="#"><span class="glyphicon d-loper"></span>توسعه دهنده</a>
                </div>
            </div>
        </div>
    </div>
</section>