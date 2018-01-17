
$( document ).ready(function() {

    $('#send-comment').click(function () {

        var content = $(this).parent('.form-group').children('#comment-form-text').val();
        var parentId = $(this).parent('.form-group').children('#comment-form-parent-id').val();
        var taskId = $(this).data('task-id');

        $.ajax({
            url: '/task/comment',
            type: 'POST',
            data: {'CommentModel[content]': content,
                   'CommentModel[parentId]': parentId,
                   'CommentModel[taskId]': taskId},
            success: function (response) {
                location.reload(true);
                location.href = response;
            }
        });

        return false;
    });

    $('.comment-reply').click(function () {

        var commentId = $(this).parents('.comment').data('comment-id');
        var commentNumber = $(this).parents('.media-right').find('.comment-number').text();

        //очищаем номер комментария от #
        commentNumber = commentNumber.split('#')[1];

        //выводим номер комментария у формы к которому адресован текущий
        $('.comment-parent-number').text('#' + commentNumber);
        //задаем скрытое поле(parent-id) у формы
        $('#comment-form-parent-id').attr('value', commentId);
        $('.comment-form-parent-information').css({'display': 'block'});
    });

    $('.comment-form-delete-parent').click(function () {

        var parentInformation = $('.comment-form-parent-information');

        //скрываем div с номером родительского комметария
        $('#commentform-parentid').attr('value', '');
        parentInformation.css({'display': 'none'});
    })
});