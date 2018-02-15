$(document).ready(function () {
    $('.delete-task-btn').click(function () {

        var taskId = $(this).data('task-id');
        var message = 'Вы действительно хотите удалить данную задачу';

        if(confirm(message)){
            $.ajax({
                url: '/task/delete',
                type: 'POST',
                data: {'DeleteTaskModel[id]': taskId},
                success: function (response) {
                    location.href = response;
                }
            });
        }

        return false;
    });
});