
$( document ).ready(function() {
    $('.comment-reply').click(function () {

        var commentId = $(this).parents('.comment').data('comment-id');

        var commentNumber = $(this).parents('.media-right').find('.comment-number').text();

        commentNumber = commentNumber.split('#')[1];

        $('.comment-parent-number').text('#' + commentNumber);

        $('#commentform-parentid').attr('value', commentId);

        $('.comment-form-parent-information').css({'display': 'block'});
    });

    $('.comment-form-delete-parent').click(function () {

        var parentInformation = $('.comment-form-parent-information');

        $('#commentform-parentid').attr('value', '');

        parentInformation.css({'display': 'none'});
    })
});