<?php
if(!isset($prefix))
    $prefix = '';
?>

<?php if(Yii::app()->user->hasFlash($prefix.'success')):?>
    <div class="callout callout-success fade in">
        <p><?php echo Yii::app()->user->getFlash($prefix.'success');?></p>
    </div>
<?php elseif(Yii::app()->user->hasFlash($prefix.'failed')):?>
    <div class="callout callout-danger fade in">
        <p><?php echo Yii::app()->user->getFlash($prefix.'failed');?></p>
    </div>
<?php elseif(Yii::app()->user->hasFlash($prefix.'warning')):?>
    <div class="callout callout-warning fade in">
        <p><?php echo Yii::app()->user->getFlash($prefix.'warning');?></p>
    </div>
<?php endif;?>
