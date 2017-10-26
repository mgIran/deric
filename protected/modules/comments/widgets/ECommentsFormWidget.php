<?php
/**
 * ECommentsFormWidget class file.
 *
 * @author Dmitry Zasjadko <segoddnja@gmail.com>
 * @link https://github.com/segoddnja/ECommentable
 */

/**
 * Widget for view comments form for current model
 *
 * @version 1.0
 * @package Comments module
 */
Yii::import('comments.widgets.ECommentsBaseWidget');
class ECommentsFormWidget extends ECommentsBaseWidget
{
    /**
     * Is used for display validation errors
     * @var Comment newComment
     */
    public $validatedComment;

    public $isDevReply = false;

    public function run()
    {
        if ($this->registeredOnly === false || Yii::app()->user->isGuest === false) {
            $this->render('ECommentsFormWidget', array(
                'newComment' => $this->validatedComment ? $this->validatedComment : $this->createNewComment(),
                'isDevReply' => $this->isDevReply
            ));
        } else {
            echo Yii::t($this->_config['translationCategory'], 'For add new ' . $this->_config['moduleObjectName'] . ' should be signed up.');
            echo '<a data-toggle="modal" href="#login-modal">' . Yii::t($this->_config['translationCategory'], 'Log In') . '</a>';
            echo '&nbsp;' . Yii::t($this->_config['translationCategory'], 'or') . '&nbsp;';
            echo '<a target="_blank" href="' . Yii::app()->baseUrl . '/#signup' . '">' . Yii::t($this->_config['translationCategory'], 'Sign Up.') . '</a>';
        }
    }
}
?>