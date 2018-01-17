$(document).ready(function () {
    $('.project-join').click(function () {

        var userId = $(this).data('user-id');
        var projectId = $(this).data('project-id');

        var elem = $(this);

        $.ajax({
            url: '/project/join',
            type: 'POST',
            data: {
                'JoinToProjectModel[userId]': userId,
                'JoinToProjectModel[projectId]': projectId
            },
            success: function (response) {
                elem.replaceWith('<code>На рассмотрении</code>');
            },
        });

        return false;
    });
});