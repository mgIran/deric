
<div class="footer">
    <div class="logo">
        <img src="<?= Yii::app()->theme->baseUrl.'/images/logo.png' ?>">
    </div>
    <div class="text-logo">
        <b>سیسن اپ، بزرگترین مرجع دانلود برنامه ها و بازی های واقعیت مجازی</b>
    </div>
    <div class="arm">
        <div class="arm-nahad">footer-logo.png
            <a class="right" href="#">
                <img src="images/logo-right.png">
            </a>
            <a class="left" href="#">
                <img src="images/logo-left.png">
            </a>
        </div>
    </div>
    <div class="social">
        <div class="social-il">
            <a class="social-icon" href="#"><span class="telegram"></span></a>
            <a class="social-icon" href="#"><span class="instagram"></span></a>
            <a class="social-icon" href="#"><span class="facebook"></span></a>
            <a class="social-icon" href="#"><span class="utube"></span></a>
            <a class="social-icon" href="#"><span class="aparat"></span></a>
        </div>
    </div>
    <div class="menu">
        <ul class="menu-footer">
            <li><a href="<?= Yii::app()->createUrl('/site/privacy'); ?>">حریم شخصی</a></li>
            <li><a href="<?= Yii::app()->createUrl('/site/terms'); ?>">شرایط استفاده</a></li>
            <li><?php if(isset(Yii::app()->user->roles) and Yii::app()->user->roles=='developer'):?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel'); ?>">توسعه دهندگان</a>
                <?php else:?>
                    <a href="<?= Yii::app()->createUrl('/developers/panel/signup/step/agreement'); ?>">توسعه دهندگان</a>
                <?php endif;?></li>
            <li><a href="<?= Yii::app()->createUrl('/site/about');?>">درباره ما</a></li>
            <li><a href="<?= Yii::app()->createUrl('/site/contactUs'); ?>">تماس با ما</a></li>
        </ul>
    </div>
    <div class="copyright">
        <div class="text-center">کپی رایت @ سیسن اپ - 2018-1396 - تمامی حقوق محفوظ است.</div>
    </div>
</div>