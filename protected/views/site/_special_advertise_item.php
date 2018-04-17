<?php
/* @var $data AppAdvertises */
$detail = $data->getShowDetail();
?>
<section class="baner baner-two">
    <div class="baner-box">
        <img class="baner-full" src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$data->cover ?>"
             alt="<?= CHtml::encode($data->title) ?>">
        <div class="baner-box-to">
            <div class="baner-item">
                <div class="media">
                    <div class="media-right photo hidden-sm hidden-xs">
                        <div class="media-right-inner">
                            <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$data->cover ?>"
                                 alt="<?= CHtml::encode($data->title) ?>"
                                 class="media-object media-img">
                        </div>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading"><a href="<?= $detail->url ?>"><?= $data->title ?></a></h5>
                        <a class="btn downloady" href="<?= $detail->downloadUrl ?>">دانلود</a>
                        <div class="votes hidden-md hidden-lg">
                            <div class="free"><?php if($detail->price>0):?>
                                    <?= Controller::parseNumbers($detail->price) ?>
                                <?php else:?>
                                    رایگان
                                <?php endif;?></div>
                            <div class="star"><?= Controller::printRateStars(doubleval($detail->rate)) ?></div>
                        </div>
                        <div class="text-justify texts"><?= $detail->summary ?></div>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="det-box">
                    <span class="glyphicon d-load"></span><span class="text-box"><?= Controller::parseNumbers(number_format($detail->download)) ?> دانلود</span>
                </div>
                <div class="det-box">
                    <span class="glyphicon a-roid"></span><span class="text-box"><?= $data->platform->title ?></span>
                </div>
                <div class="det-box">
                    <span class="glyphicon v-lume"></span><span class="text-box">حجم:<?= Controller::parseNumbers($detail->size) ?></span>
                </div>
                <?php
                if($data->app_id || $detail->developer):
                    ?>
                    <div class="det-box hidden-xs">
                        <a<?php
                        echo ' href="'.$this->createUrl('apps/developer?title='.($data->app->developer?urlencode($data->app->developer->userDetails->developer_id).'&id='.$data->app->developer_id:urlencode($data->app->developer_team).'&t=1')).'"';
                        ?>>
                            <span class="glyphicon d-loper"></span><?= $detail->developer ?>
                        </a>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</section>
