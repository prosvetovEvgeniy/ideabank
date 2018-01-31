
$( document ).ready(function() {
    $('.delete-file-btn').click(function () {

        var fileId = $(this).data('file-id');
        var taskId = $(this).data('task-id');
        var elem = $(this).parent();

        var msg = 'Вы действительно хотите удалить файл';

        if(confirm(msg))
        {
            $.ajax({
                url: '/task-file/delete',
                type: 'POST',
                data: {'TaskFileDeleteModel[id]': fileId, 'TaskFileDeleteModel[taskId]': taskId},
                success: function (response) {
                    elem.hide();
                }
            });
        }
    });
});