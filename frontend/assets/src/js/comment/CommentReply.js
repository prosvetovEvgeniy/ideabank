
$( document ).ready(function() {
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
        $('#comment-form-parent-id').attr('value', '');
        parentInformation.css({'display': 'none'});
    })
});