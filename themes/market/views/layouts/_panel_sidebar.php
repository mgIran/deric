<div class="side-bar">
    <div class="scroll-container">
        <h5>کاربری</h5>
        <ul>
            <li>
                <a href="<?php echo Yii::app()->createUrl("/dashboard?tab=credit-tab");?>">
                    <i class="icon dashboard-icon"></i>
                    <span>داشبورد</span>
                </a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl("/dashboard?tab=transactions-tab");?>">
                    <i class="icon transaction-icon"></i>
                    <span>تراکنش ها</span>
                </a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl("/dashboard?tab=buys-tab");?>">
                    <i class="icon cart-icon"></i>
                    <span>خریدها</span>
                </a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl("/dashboard?tab=bookmarks-tab");?>">
                    <i class="icon heart-icon"></i>
                    <span>نشان شده ها</span>
                </a>
            </li>
            <li>
                <a href="<?= $this->createUrl('/tickets/manage/'); ?>">
                    <i class="icon support-icon"></i>
                    <span>پشتیبانی</span>
                </a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl("/dashboard?tab=setting-tab");?>">
                    <i class="icon setting-icon"></i>
                    <span>تنظیمات</span>
                </a>
            </li>
            <?php if(Yii::app()->user->roles!='developer'):?>
                <li class="upgrade-link">
                    <a href="<?php echo Yii::app()->createUrl("/developers/panel/signup/step/agreement");?>">
                        <i class="icon white-user-icon"></i>
                        <span>توسعه دهنده شوید</span>
                    </a>
                </li>
            <?php endif;?>
        </ul>
        <?php if(Yii::app()->user->roles == 'developer'):?>
            <h5>توسعه دهندگان</h5>
            <ul class="developers-menu">
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel');?>">
                        <i class="icon phone-icon"></i>
                        <span>برنامه ها</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel/discount');?>">
                        <i class="icon discount-icon"></i>
                        <span>تخفیفات</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel/account');?>">
                        <i class="icon user-icon"></i>
                        <span>حساب توسعه دهنده</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel/sales');?>">
                        <i class="icon chart-icon"></i>
                        <span>گزارش فروش</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel/settlement');?>">
                        <i class="icon payment-icon"></i>
                        <span>تسویه حساب</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/tickets/manage?dev=1');?>">
                        <i class="icon support-icon"></i>
                        <span>پشتیبانی</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('/developers/panel/documents');?>">
                        <i class="icon books-icon"></i>
                        <span>مستندات</span>
                    </a>
                </li>
            </ul>
        <?php endif;?>
    </div>
</div>