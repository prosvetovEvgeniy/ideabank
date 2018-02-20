$(document).ready(function () {
    $('.project-join').click(function () {

        var userId = $(this).data('user-id');
        var projectId = $(this).data('project-id');

        var elem = $(this);

        $.ajax({
            url: '/participant/join',
            type: 'POST',
            data: {
                'JoinParticipantModel[userId]': userId,
                'JoinParticipantModel[projectId]': projectId
            },
            success: function (response) {
                elem.replaceWith('<code>На рассмотрении</code>');
            },
        });

        return false;
    });
});