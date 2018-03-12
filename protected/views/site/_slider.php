<?php
/** @var $this Controller */
/** @var $commonAdvertises AppAdvertises[] */
if(!isset($commonAdvertises))
    $commonAdvertises  = AppAdvertises::model()->findAll(AppAdvertises::CommonQuery($this->platform));
if($commonAdvertises):
?>
<div class="slider">
    <section id="mycarousel">
        <div class="is-carousel owl-theme" data-dots="1" data-nav="1" data-loop="1" data-items="3" data-autoplay="1" data-autoplay-hover-pause="1" data-mouseDrag="1" data-responsive='{"1200":{"items":"3"},"992":{"items":"3"},"768":{"items":"2"},"650":{"items":"1"},"0":{"items":"1"}}'>
            <?php foreach ($commonAdvertises as $advertise): ?>
                <div class="item">
                    <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$advertise->cover ?>">
                    <div class="carousel-caption">
                        <h4><?= $advertise->title ?></h4>
                    </div>
                    <a class="link-item" href="<?= $advertise->getUrl() ?>"></a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php
endif;