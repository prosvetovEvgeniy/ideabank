$(document).ready(function () {
    $('.leave-project').click(function () {

        var participantId = $(this).data('participant-id');

        var question = 'Вы действительно хотите покинуть данный проект?';

        if(confirm(question))
        {
            $.ajax({
                url: '/participant/delete',
                type: 'POST',
                data: {'DeleteParticipantModel[id]': participantId},
                success: function (response) {
                    location.reload();
                }
            });
        }

        return false;
    });
});