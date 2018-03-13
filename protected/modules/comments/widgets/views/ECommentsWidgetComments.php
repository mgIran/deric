<?php
/* @var $comments array */
/* @var $comment Comment */
?>
<div class="opinion">
    <div class="opinion-container">
        <h5><b>نظرات</b></h5>
        <?php if(count($comments) > 0):?>
            <div class="opinion-to nicescroll" data-cursorcolor="#00381d" data-cursorborder="none" data-railpadding='js:{"top":5,"right":5,"bottom":0,"left":5}' data-autohidemode="leave">
                <?php foreach($comments as $key => $comment):?>
                    <?php $parentComment=Comment::model()->findByPk($comment->parent_comment_id);?>
                    <?php if($comment->is_private):?>
                        <?php if($parentComment->creator_id != Yii::app()->user->getId() and $comment->creator_id != Yii::app()->user->getId()):?>
                            <?php continue;?>
                        <?php endif;?>
                    <?php endif;?>
                    <div id="comment-<?php echo $comment->comment_id; ?>" class="opinion-item">
                        <div class="profile">
                            <div class="profile-bg empty">
                                <?php
                                if($comment->avatarLink && !empty($comment->avatarLink) && file_exists($comment->avatarLink))
                                    echo '<img src="'.$comment->avatarLink.'" alt="'.$comment->userName.'">';
                                else
                                    echo "<b>".strtoupper(mb_substr($comment->userName, 0, 1))."</b>";
                                ?>
                            </div>
                        </div>
                        <h6><b><?= $comment->userName ?></b></h6>
                        <div class="votes">
                            <div class="star"><?= Controller::printRateStars($comment->getUserRate()) ?></div>
                        </div>
                        <p dir="auto" class="texts"><?= strip_tags($comment->comment_text) ?></p>
                        <div class="like">
                            <small style="padding-top: 10px" class="text-left  pull-left"><?php echo JalaliDate::differenceTime($comment->create_time);?></small>
<!--                            <div class="liked like-down">-->
<!--                                <a href="#">-->
<!--                                    <i class="glyphicon like-down-icon"></i>-->
<!--                                    <span class="hidden-sm hidden-xs">0</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                            <div class="liked like-up">-->
<!--                                <a href="#">-->
<!--                                    <i class="glyphicon like-up-icon"></i>-->
<!--                                    <span class="hidden-sm hidden-xs">333</span>-->
<!--                                </a>-->
<!--                            </div>-->
                        </div>
                        <?php if($this->adminMode === true):
                            if(Yii::app()->user->type == 'admin' ||
                                (Yii::app()->user->roles == 'developer' && $this->model->developer_id == Yii::app()->user->getId())
                            ):
                                ?>
                                <div class="admin-panel">
                                    <?php if($this->_config['premoderate'] === true && ($comment->status === null || $comment->status == Comment::STATUS_NOT_APPROWED)) {
                                        echo CHtml::link(Yii::t($this->_config['translationCategory'], 'approve'), Yii::app()->urlManager->createUrl(
                                            CommentsModule::APPROVE_ACTION_ROUTE, array('id'=>$comment->comment_id)
                                        ), array('class'=>'text-success approve'));
                                    }?>
                                    <?php echo CHtml::link(Yii::t($this->_config['translationCategory'], 'delete'), Yii::app()->urlManager->createUrl(
                                        CommentsModule::DELETE_ACTION_ROUTE, array('id'=>$comment->comment_id)
                                    ), array('class'=>'text-danger delete'));?>
                                </div>
                            <?php endif;
                        endif; ?>
                        <?php
                        if($this->adminMode === true && $this->allowSubcommenting === true && ($this->registeredOnly === false || Yii::app()->user->isGuest === false))
                        {
                            if(Yii::app()->user->type == 'admin' || (Yii::app()->user->roles == 'developer' && $this->model->developer_id == Yii::app()->user->getId())){
                                echo CHtml::link(Yii::t($this->_config['translationCategory'] ,'Reply') ,'#reply-' . $comment->comment_id ,array(
                                    'data-comment-id' => $comment->comment_id ,
                                    'class' => 'btn btn-info collapsed add-comment' ,
                                    'data-toggle' => 'collapse' ,
                                    'data-parent' => '#comment-' . $comment->comment_id
                                ));
                                echo "<div class='comment-form comment-form-outer collapse' id='reply-" . $comment->comment_id . "'>";
                                Yii::app()->controller->renderPartial('//layouts/_loading');
                                $this->widget('comments.widgets.ECommentsFormWidget' ,array(
                                    'model' => $this->model ,
                                    'isDevReply' => (Yii::app()->user->roles=='developer')?true:false
                                ));
                                echo "</div>";
                            }
                        }
                        ?>
                        <?php if(count($comment->childs) > 0 && $this->allowSubcommenting === true) $this->render('ECommentsWidgetComments', array('comments' => $comment->childs));?>
                    </div>
                    <?php
                    /* Last Version */
                    /*

                        <div class="comment-avatar">

                        </div>
                        <div class="comment-header">
                            <span class="comment-name"><?php echo $comment->userName;?></span>
                            <span><?php if(!$comment->status) echo '<small class="text-danger">(تایید نشده)</small>' ?></span>
                            <span class="comment-date"><?php echo JalaliDate::differenceTime($comment->create_time);?></span>
                        </div>
                        <p dir="auto">
                            <?php echo $comment->comment_text;?>
                        </p>

                        <?php if($this->adminMode === true):
                            if(Yii::app()->user->type == 'admin' ||
                                (Yii::app()->user->roles == 'developer' && $this->model->developer_id == Yii::app()->user->getId())
                            ):
                                ?>
                                <div class="admin-panel">
                                    <?php if($this->_config['premoderate'] === true && ($comment->status === null || $comment->status == Comment::STATUS_NOT_APPROWED)) {
                                        echo CHtml::link(Yii::t($this->_config['translationCategory'], 'approve'), Yii::app()->urlManager->createUrl(
                                            CommentsModule::APPROVE_ACTION_ROUTE, array('id'=>$comment->comment_id)
                                        ), array('class'=>'text-success approve'));
                                    }?>
                                    <?php echo CHtml::link(Yii::t($this->_config['translationCategory'], 'delete'), Yii::app()->urlManager->createUrl(
                                        CommentsModule::DELETE_ACTION_ROUTE, array('id'=>$comment->comment_id)
                                    ), array('class'=>'text-danger delete'));?>
                                </div>
                            <?php endif;
                        endif; ?>
                        <?php
                        if($this->adminMode === true && $this->allowSubcommenting === true && ($this->registeredOnly === false || Yii::app()->user->isGuest === false))
                        {
                            if(Yii::app()->user->type == 'admin' || (Yii::app()->user->roles == 'developer' && $this->model->developer_id == Yii::app()->user->getId())){
                                echo CHtml::link(Yii::t($this->_config['translationCategory'] ,'Reply') ,'#reply-' . $comment->comment_id ,array(
                                    'data-comment-id' => $comment->comment_id ,
                                    'class' => 'btn btn-info collapsed add-comment' ,
                                    'data-toggle' => 'collapse' ,
                                    'data-parent' => '#comment-' . $comment->comment_id
                                ));
                                echo "<div class='comment-form comment-form-outer collapse' id='reply-" . $comment->comment_id . "'>";
                                Yii::app()->controller->renderPartial('//layouts/_loading');
                                $this->widget('comments.widgets.ECommentsFormWidget' ,array(
                                    'model' => $this->model ,
                                    'isDevReply' => (Yii::app()->user->roles=='developer')?true:false
                                ));
                                echo "</div>";
                            }
                        }
                        ?>
                        <?php if(count($comment->childs) > 0 && $this->allowSubcommenting === true) $this->render('ECommentsWidgetComments', array('comments' => $comment->childs));?>
                    */?>
                <?php endforeach; ?>
            </div>
        <?php else:?>
            <?php echo Yii::t($this->_config['translationCategory'], 'No '.$this->_config['moduleObjectName'].'s');?>
        <?php endif; ?>
    </div>
</div>


<script>
function checkRtl( character ) {
    var RTL = ['ا','ب','پ','ت','س','ج','چ','ح','خ','د','ذ','ر','ز','ژ','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ک','گ','ل','م','ن','و','ه','ی'];
    return RTL.indexOf( character ) > -1;
}

function checkChar( character ) {
    if (character.match(/\s/) || character.match(/[0-9-!@#$%^&()_+|~=`{}\[\]:";\'<>?,.\/]/))
        return true;
    else
        return false;
}
var pTags = $(".comments-list").find("p");
pTags.each(function(){
    var firstChar = $(this).text().trim().substr(1,1);
    var $i=3;
    while(checkChar(firstChar) && $i < $(this).text().trim().length)
    {
        firstChar = $(this).text().trim().substr($i,1);
        console.log(firstChar);
        $i++;
    }
    if( checkRtl(firstChar) ) {
        $(this).removeClass("ltr").addClass("rtl");
    } else {
        $(this).removeClass("rtl").addClass("ltr");
    }
});
</script>

