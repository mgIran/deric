<div class="comment-widget <?= Yii::app()->language != 'fa_ir'?'en':'' ?>" id="<?php echo $this->id?>">
    <div class="scoring">
        <h5><b>امتیازدهی و نظرات</b></h5>
        <?php
        if($this->showPopupForm === true)
        {
            if($this->registeredOnly === false || Yii::app()->user->isGuest === false)
            {
                echo "<div class='comment-form' id=\"addCommentDialog-{$this->id}\">";
                Yii::app()->controller->renderPartial('//layouts/_loading');
                $this->widget('comments.widgets.ECommentsFormWidget', array(
                    'model' => $this->model,
                ));
                echo "</div>";
            }
        }
        if($this->registeredOnly === true && Yii::app()->user->isGuest === true)
        {
            // @todo change login and signup links
            Yii::app()->user->returnUrl = Yii::app()->request->url;
            echo '<h5 class="last">برای ارسال نظر و امتیازدهی باید عضو سایت باشید.';
            echo '&nbsp;<a href="'.Yii::app()->createUrl('/login').'">'.Yii::t($this->_config['translationCategory'], 'Log In').'</a>';
            echo '&nbsp;'.Yii::t($this->_config['translationCategory'],'or').'&nbsp;';
            echo '<a target="_blank" href="'.Yii::app()->createUrl('/register').'">'.Yii::t($this->_config['translationCategory'], 'Sign Up.').'</a>';
            echo '</h5>';
        }
        ?>
    </div>
    <?php
    $this->render('ECommentsWidgetComments', array('newComment' => $newComment ,'comments' => $comments));
    ?>
</div>
