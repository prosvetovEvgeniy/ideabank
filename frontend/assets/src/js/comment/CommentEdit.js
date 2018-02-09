
$( document ).ready(function() {

    $('.comment-edit-icon').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');
        var btnsSelector = $(this).parents('.media-right').children('.btn-edit-group')

        commentSelector.attr('contenteditable', true);
        commentSelector.css('background-color', '#F4F47B');

        btnsSelector.css('display', 'block');
    });

    $('.change-comment').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');
        var btnsSelector = $(this).parent();

        var commentContent = commentSelector.text().trim();
        var commentId = commentSelector.data('comment-id');

        $.ajax({
            url: '/comment/edit',
            type: 'POST',
            data: {'CommentEditModel[id]': commentId,
                   'CommentEditModel[content]': commentContent},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.cancel-edit-comment').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');
        var btnsSelector = $(this).parent();

        commentSelector.attr('contenteditable', false);
        commentSelector.css('background-color', '#e9e9ec');

        btnsSelector.css('display', 'none');
    });

    $('.comment-delete-icon').click(function () {
        var commentId = $(this).data('comment-id');

        var message = 'Вы действительно хотите удалить комментарий';

        if(confirm(message))
        {
            $.ajax({
                url: '/comment/delete',
                type: 'POST',
                data: {'CommentDeleteModel[id]': commentId},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });

    $('.comment-reestablish').click(function () {
        var commentId = $(this).data('comment-id');

        $.ajax({
            url: '/comment/reestablish',
            type: 'POST',
            data: {'CommentReestablishModel[id]': commentId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.comment-make-private-icon').click(function () {
        var commentId = $(this).data('comment-id');

        $.ajax({
            url: '/comment/make-private',
            type: 'POST',
            data: {'CommentPrivateModel[id]': commentId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.comment-make-public-icon').click(function () {
        var commentId = $(this).data('comment-id');

        $.ajax({
            url: '/comment/make-public',
            type: 'POST',
            data: {'CommentPublicModel[id]': commentId},
            success: function (response) {
                location.reload();
            }
        });
    });
});