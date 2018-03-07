<footer class="footer">
    <div class="panel panel-default enamad-mobile hidden-lg hidden-md">
        <div class="panel-body">
            <img id='xlapsguihwlargvldrft' style='cursor:pointer' onclick='window.open("http://trustseal.enamad.ir/Verify.aspx?id=30451&p=fuixdrfsodshyncrnbpd", "Popup","toolbar=no, location=no, statusbar=no, menubar=no, scrollbars=1, resizable=0, width=580, height=600, top=30")' alt='' src='http://trustseal.enamad.ir/logo.aspx?id=30451&p=vjymgthvaodsfujylznb'/>
        </div>
    </div>
    <nav>
        <ul class="nav nav-list">
            <li>
                <a href="<?= Yii::app()->createUrl('/site/privacy'); ?>"></a>
                حریم شخصی
            </li><li>
                <a href="<?= Yii::app()->createUrl('/site/terms'); ?>"></a>
                شرایط استفاده از خدمات
            </li><li>
                <?php if(isset(Yii::app()->user->roles) and Yii::app()->user->roles=='developer'):?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel'); ?>"></a>
                <?php else:?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel/signup/step/agreement'); ?>"></a>
                <?php endif;?>
                توسعه دهندگان
            </li><li>
                <a href="<?= Yii::app()->createUrl('/site/help');?>"></a>
                راهنما
            </li><li>
                <a href="<?= Yii::app()->createUrl('/site/contactUs'); ?>"></a>
                تماس با ما
            </li><li>
                <a href="<?= Yii::app()->createUrl('/site/about');?>"></a>
                درباره ما
            </li>
            <li class="copyright ltr" >
                &copy;
                <strong><?php echo date('Y');?></strong>
                Hyper Apps
            </li>
        </ul>
    </nav>
    <div class="rahbod pull-left">
        <a href="https://t.me/rahbod" target="_blank" title="Rahbod"><img src="<?php echo Yii::app()->theme->baseUrl.'/images/rahbod.svg';?>"></a>
    </div>
</footer>