$(document).ready(function () {

    $('.block-tag').click(function () {
        var participantId = $(this).parent().data('participant-id');

        $.ajax({
            url: '/participant/block',
            type: 'POST',
            data: {'BlockParticipantModel[id]': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.add-tag').click(function () {
        var participantId = $(this).parent().data('participant-id');

        $.ajax({
            url: '/participant/add',
            type: 'POST',
            data: {'AddParticipantModel[id]': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.un-block-tag').click(function () {
        var participantId = $(this).parent().data('participant-id');
        console.log(participantId);
        $.ajax({
            url: '/participant/un-block',
            type: 'POST',
            data: {'UnBlockParticipantModel[id]': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.cancel-tag').click(function () {
        var participantId = $(this).parent().data('participant-id');
        console.log(participantId);
        $.ajax({
            url: '/participant/cancel',
            type: 'POST',
            data: {'CancelParticipantModel[id]': participantId},
            success: function (response) {
                location.reload();
            }
        });
    });
});