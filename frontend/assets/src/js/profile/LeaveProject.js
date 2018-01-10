$(document).ready(function () {
    $('.leave-project').click(function () {

        var participantId = $(this).data('participant-id');

        var question = 'Вы действительно хотите покинуть данный проект?';

        if(confirm(question))
        {
            $.ajax({
                url: '/profile/leave-project',
                type: 'POST',
                data: {'participantId': participantId},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });

    $('.join-to-project').click(function () {

        var participantId = $(this).data('participant-id');

        $.ajax({
            url: '/profile/join-to-project',
            type: 'POST',
            data: {'participantId': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.delete-participant').click(function () {

        var participantId = $(this).data('participant-id');

        $.ajax({
            url: '/profile/delete-participant',
            type: 'POST',
            data: {'participantId': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });
});