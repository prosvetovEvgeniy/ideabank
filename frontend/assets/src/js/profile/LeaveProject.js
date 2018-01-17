$(document).ready(function () {
    $('.leave-project').click(function () {

        var participantId = $(this).data('participant-id');

        var question = 'Вы действительно хотите покинуть данный проект?';

        if(confirm(question))
        {
            $.ajax({
                url: '/profile/delete-participant',
                type: 'POST',
                data: {'DeleteParticipantModel[participantId]': participantId},
                success: function (response) {
                    location.reload();
                }
            });
        }

        return false;
    });
});