<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-right image">
                <img src="<?= Yii::app()->theme->baseUrl ?>/img/user2-160x160.jpg" class="img-circle" alt="تصویر مدیر">
            </div>
            <div class="pull-right info">
                <p><?php echo Yii::app()->user->username?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> آنلاین</a>
            </div>
        </div>

        <!-- search form (Optional) -->
<!--        <form action="#" method="get" class="sidebar-form">-->
<!--            <div class="input-group">-->
<!--                <input type="text" name="q" class="form-control" placeholder="Search...">-->
<!--              <span class="input-group-btn">-->
<!--                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>-->
<!--              </span>-->
<!--            </div>-->
<!--        </form>-->
        <!-- /.search form -->
        <?php $this->widget( 'zii.widgets.CMenu', array(
            'htmlOptions' => array( 'class' => 'sidebar-menu' ),
            'submenuHtmlOptions' => array( 'class' => 'dropdown-menu' ),
            'encodeLabel' => false,
            'items' => Controller::createAdminMenu()
        ) ); ?>
    </section>
    <!-- /.sidebar -->
</aside>