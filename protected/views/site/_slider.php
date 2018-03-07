<?php
/** @var $this Controller */
/** @var $advertises Advertises[] */
if($advertises):
?>
<div class="slider">
    <section id="mycarousel">
        <div class="is-carousel owl-theme" data-dots="1" data-nav="1" data-loop="1" data-items="3" data-autoplay="1" data-autoplay-hover-pause="1" data-mouseDrag="1" data-responsive='{"1200":{"items":"3"},"992":{"items":"3"},"768":{"items":"2"},"650":{"items":"1"},"0":{"items":"1"}}'>
            <?php foreach ($advertises as $slide): ?>
                <div class="item">
                    <img src="<?= Yii::app()->getBaseUrl(true).'/uploads/advertises/'.$slide->cover ?>">
                    <div class="carousel-caption">
                        <h4><?= $slide->tit ?></h4>
                    </div>
                    <a class="link-item" href="#"></a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php
endif;