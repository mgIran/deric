<div class="comment-widget <?= Yii::app()->language != 'fa_ir'?'en':'' ?>" id="<?php echo $this->id?>">
<?php
//    echo '<div class="comment-form-outer col-lg-4 col-md-4 col-sm-5 col-xs-12" id="comment-form" >';
    echo '<div class="comment-form-outer" id="comment-form" >';
    if($this->showPopupForm === true)
    {
        if($this->registeredOnly === false || Yii::app()->user->isGuest === false)
        {
            Yii::app()->controller->renderPartial('//layouts/_loading');
            echo "<div class='comment-form collapse' id=\"addCommentDialog-{$this->id}\">";
            echo '<h4>'.Yii::t($this->_config['translationCategory'], 'Send '.ucfirst($this->_config['moduleObjectName'])).'</h4>';
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
        echo Yii::t($this->_config['translationCategory'], 'To add any '.$this->_config['moduleObjectName'].', you should sign up first.');
        echo '&nbsp;<a href="'.Yii::app()->createUrl('/login').'">'.Yii::t($this->_config['translationCategory'], 'Log In').'</a>';
        echo '&nbsp;'.Yii::t($this->_config['translationCategory'],'or').'&nbsp;';
        echo '<a target="_blank" href="'.Yii::app()->createUrl('/register').'">'.Yii::t($this->_config['translationCategory'], 'Sign Up.').'</a>';
    }
    echo "</div>";
//    echo '<div class="comments-list-outer col-lg-8 col-md-8 col-sm-7 col-xs-12">';
    echo '<div class="comments-list-outer">';
echo "<button class='btn btn-default pull-left' data-toggle=\"collapse\" data-target=\"#addCommentDialog-{$this->id}\" ><span class='icon icon-pencil'></span> نظرتان را بگویید</button>";
    echo '<h4>'.Yii::t($this->_config['translationCategory'], 'Users '.ucfirst($this->_config['moduleObjectName'])).'</h4>';
    $this->render('ECommentsWidgetComments', array('newComment' => $newComment ,'comments' => $comments));
    echo '</div>';
?>
</div>
