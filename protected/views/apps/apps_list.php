<?php
/* @var $this AppsController */
/* @var $dynamicRows RowsHomepage[] */
/* @var $title String */
/* @var $pageTitle String */
/* @var $id int */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
?>

<div class="app-box">
    <?php $this->renderPartial('/site/_slider') ?>
    <div class="game-title">
        <div class="game-title-to">
            <h4><b><?php echo CHtml::encode($pageTitle).((!is_null($title))?'ی '.CHtml::encode($title):null)?></b></h4>
        </div>
    </div>
    <?php
    $cats = AppCategories::model()->getCategoryChilds($id);
    foreach ($dynamicRows as $dynamicRow):
        $dp = Apps::model()->findAll($dynamicRow->getConstCriteria(Apps::getValidApps($this->platform, $cats)));
        ?>
        <section>
            <div class="see">
                <div class="row">
                    <div class="see-all col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a class="link-grid" href="<?= $dynamicRow->query ?>"><span>مشاهده همه</span><span class="grid"></span></a>
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
</div>