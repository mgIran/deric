<?php
/**
 * @var $type string
 * @var $message string
 * @var $closeButton string
 */
?>
<div class="alert alert-<?= $type ?> fade in">
    <?if(isset($closeButton) && $closeButton):?><button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button><?endif;?>
    <?php echo $message ?>
</div>