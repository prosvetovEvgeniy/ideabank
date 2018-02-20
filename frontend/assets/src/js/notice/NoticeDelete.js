$(document).ready(function () {
    $('.notice-delete').click(function () {

        var id = $(this).data('id');
        var question = 'Вы действительно хотите удалить заметку';

        if(confirm(question))
        {
            $.ajax({
                url: '/notice/delete',
                type: 'POST',
                data: {'NoticeDeleteModel[id]': id},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
});