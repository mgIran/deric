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
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
?>

<?php $this->renderPartial('/site/_slider', compact('commonAdvertises')) ?>
<?php $this->renderPartial('/site/_quick_access') ?>

<?php foreach ($dynamicRows as $dynamicRow):
    $dp = Apps::model()->findAll($dynamicRow->getConstCriteria(Apps::getValidApps($this->platform, ($dynamicRow->const_query?AppCategories::CategoryChildes($dynamicRow->const_category):CHtml::listData($dynamicRow->categoryIds,'id', 'app_category_id')))));
    ?>
    <section>
        <div class="see">
            <div class="row">
                <div class="see-all col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a class="link-grid" href="<?= $this->createUrl('/apps/all/'.($dynamicRow->query?:(Controller::sefLink($dynamicRow->title).'/'.$dynamicRow->id))) ?>"><span>مشاهده همه</span><span class="grid"></span></a>
                    <a class="novelty" href="#"><?= $dynamicRow->title ?></a>
                </div>
            </div>
        </div>
        <div class="game">
            <div class="imgs">
                <div id="demo2" class="is-carousel"  data-items="8" data-dots="0" data-nav="1" data-mouse-drag="1" data-responsive='{"1366":{"items":"8"},"1200":{"items":"6"},"992":{"items":"5"},"768":{"items":"4"},"650":{"items":"4"},"0":{"items":"4"}}'>
                    <?php if($dp): ?>
                        <?php foreach ($dp as $data): $this->renderPartial('//site/_app_item', compact('data')); endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php
endforeach;
?>
<?php
if($specialAdvertises) {
    foreach ($specialAdvertises as $specialAdvertise)
        $this->renderPartial('_special_advertise_item', ['data' => $specialAdvertise]);
}
?>
