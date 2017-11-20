(function($) {

    /**
     * commentsList set function.
     * @param options map settings for the comments list. Availablel options are as follows:
     * - deleteConfirmString
     * - approveConfirmString
     */
    $.fn.commentsList = function (options) {
        return this.each(function () {
            var settings = $.extend({}, $.fn.commentsList.defaults, options || {});
            var $this = $(this);
            var id = $this.attr('id');
            $.fn.commentsList.settings[id] = settings;
            $.fn.commentsList.initDialog(id);
            $this
                .delegate('.delete', 'click', function () {
                    var id = $($(this).parents('.comment-widget')[0]).attr("id");
                    if (confirm($.fn.commentsList.settings[id]['deleteConfirmString'])) {
                        $.post($(this).attr('href'))
                            .success(function (data) {
                                data = $.parseJSON(data);
                                if (data["code"] === "success") {
                                    $("#comment-" + data["deletedID"]).remove();
                                }
                            });
                    }
                    return false;
                })
                .delegate('.approve', 'click', function () {
                    var id = $($(this).parents('.comment-widget')[0]).attr("id");
                    if (confirm($.fn.commentsList.settings[id]['approveConfirmString'])) {
                        $.post($(this).attr('href'))
                            .success(function (data) {
                                data = $.parseJSON(data);
                                if (data["code"] === "success") {
                                    $("#comment-" + data["approvedID"] + " > .admin-panel > .approve").remove();
                                }
                            });
                    }
                    return false;
                })
                .delegate('.add-comment', 'click', function () {
                    var id = $($(this).parents('.comment-widget')[0]).attr("id");
                    $dialog = $(this).parents('li');
                    var commentID = $(this).data('comment-id');
                    if (commentID)
                        $('.parent_comment_id', $dialog).val(commentID);
                });
        });
    };

    $.fn.commentsList.defaults = {
        dialogTitle: 'Add comment',
        deleteConfirmString: 'Delete this comment?',
        approveConfirmString: 'Approve this comment?',
        postButton: 'Add comment',
        cancelButton: 'Cancel'
    };

    $.fn.commentsList.settings = {};

    $.fn.commentsList.initDialog = function (id) {
        var $dialog = $('#addCommentDialog-' + id);
        $dialog.data('widgetID', id);
    };

    $('body').on('click', '.comment-submit-form-btn', function (e) {
        $.fn.commentsList.postComment($(this));
    });

    $.fn.commentsList.postComment = function ($this) {
        var $form = $this.parents('form');
        $.ajax({
            url: $this.data("url"),
            data: $form.serialize(),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                $form.parents('.comment-form-outer').find('.loading-container').show();
            },
            success: function (data) {
                alert(data.msg);
                $form.parents('.comment-form-outer').find('.loading-container').hide();
                $form.html(data.form);
                if (data.code == "success") {
                    var list = $form.parents('.comment-widget');
                    list.html($(data.list).html());
                }
            }
        });
    };
    //
})(jQuery);