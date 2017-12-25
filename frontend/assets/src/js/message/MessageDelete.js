$(document).ready(function () {
    $('.delete-dialog').click(function () {

        var row = $(this).parents('.dialog-row');
        var companionId = row.data('companion-id');

        var question = 'Вы дейстительно хотите удалить все сообщения с пользователем ' + row.data('companion-username');

        if(confirm(question))
        {
            $.ajax({
                url: '/message/delete-dialog',
                type: 'POST',
                data: {'DeleteMessageModel[companionId]': companionId},
                success: function (response) {
                    row.fadeOut('fast');
                }
            });
        }
    });

    $('.delete-message').click(function () {

        var row = $(this).parents('.dialog-row');
        var messageId = row.data('message-id');

        $.ajax({
            url: '/message/delete-message',
            type: 'POST',
            data: {'DeleteMessageModel[messageId]': messageId},
            success: function (response) {
                row.fadeOut('fast');
            }
        });
    });
});