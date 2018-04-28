<?php
/* @var $this PublicController */
/* @var $model Users */
Yii::app()->clientScript->registerScript('bookmark','
    var selected;
    $("body").on("click", ".bookmark-remove", function(){       
        selected = $(this);
    });
');
?>
<div class="container-fluid">
    <?php if(empty($model->bookmarkedApps)):?>
        نتیجه ای یافت نشد.
    <?php else:?>

    <div class="game game-gallery mini">
        <div class="imgs img-gallery">
            <div class="gallery">
                <?php foreach($model->bookmarkedApps as $app):
                    $this->renderPartial('//site/_app_item', array('data' => $app, 'bookmark' => true));
                endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif;?>
</div>
