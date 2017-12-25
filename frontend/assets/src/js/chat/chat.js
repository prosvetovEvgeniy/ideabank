
$( document ).ready(function() {

    $('.send-message-form').on('beforeSubmit', function () {

        var formData = $(this).serializeArray();

        $.ajax({
            url: '/message/send',
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#sendmessageform-content').val('');
                $('.chat').append(response);
            }
        });
    });

    $('.send-message-form').submit(function () {
        return false;
    });

    $('.chat-header .btn-outline-danger').click(function () {

        var message = 'Вы действительно хотите удалить все сообщения?';
        var companionId = $('#sendmessageform-companionid').val();

        if(confirm(message))
        {
            $.ajax({
                url: '/message/delete-dialog',
                type: 'POST',
                data: {'DeleteMessageModel[companionId]': companionId},
                success: function (response) {
                    $('.message').hide('slow')
                }
            });
        }
    });
});