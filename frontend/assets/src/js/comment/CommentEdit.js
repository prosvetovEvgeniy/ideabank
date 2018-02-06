
$( document ).ready(function() {

    $('.comment-edit').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');
        var btnsSelector = $(this).parents('.media-right').children('.btn-edit-group')

        commentSelector.attr('contenteditable', true);
        commentSelector.css('background-color', '#F4F47B');

        btnsSelector.css('display', 'block');
    });

    $('.edit-comment').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');

        var commentContent = commentSelector.text().trim();
        var commentId = commentSelector.data('comment-id');

        $.ajax({
            url: '/task/edit-comment',
            type: 'POST',
            data: {'CommentEditModel[id]': commentId,
                   'CommentEditModel[content]': commentContent},
            success: function (response) {

            }
        });
    });

    $('.cancel-edit-comment').click(function () {
        var commentSelector = $(this).parents('.media-right').children('.comment-content');
        var btnsSelector = $(this).parents('.media-right').children('.btn-edit-group')

        commentSelector.attr('contenteditable', false);
        commentSelector.css('background-color', '#e9e9ec');

        btnsSelector.css('display', 'none');
    });
});