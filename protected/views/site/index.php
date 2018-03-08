<?
/* @var $this SiteController */
/* @var $dynamicRows RowsHomepage[] */
/* @var $latestGamesDP CActiveDataProvider */
/* @var $latestProgramsDP CActiveDataProvider */
/* @var $topDP CActiveDataProvider */
/* @var $rows CActiveDataProvider[] */
/* @var $commonAdvertises AppAdvertises[] */
/* @var $specialAdvertises AppAdvertises[] */


Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.theme.default.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
?>

<?php $this->renderPartial('/site/_slider', compact('commonAdvertises')) ?>
<?php $this->renderPartial('/site/_quick_access') ?>

<?php foreach ($dynamicRows as $dynamicRow):
    $dp = Apps::model()->findAll($dynamicRow->getConstCriteria(Apps::getValidApps($this->platform)));
    ?>
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
                        <?php if($dp): ?>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if($specialAdvertises) {
            $specialAdvertise = array_pop($specialAdvertises);
            $this->renderPartial('_special_advertise_item', ['data' => $specialAdvertise]);
        }
        ?>
    <?php
endforeach;
?>
<?php
if($specialAdvertises) {
    foreach ($specialAdvertises as $specialAdvertise)
        $this->renderPartial('_special_advertise_item', ['data' => $specialAdvertise]);
}
?>
