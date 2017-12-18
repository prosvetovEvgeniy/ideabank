
$( document ).ready(function() {

    $('.task .vote-up i').click(function () {

        var likeSelector = $(this);
        var taskSelector = likeSelector.parents('.task');
        var dislikeSelector = taskSelector.find('.vote-down i');
        var amountLikes = likeSelector.text();
        var amountDislikes = dislikeSelector.text();
        var taskId = Number(taskSelector.attr('data-task-id'));
        var currentUserLikedTask = Number(taskSelector.attr('data-current-user-liked-task'));
        var currentUserDislikedTask = Number(taskSelector.attr('data-current-user-disliked-task'));

        if(currentUserLikedTask === 0 && currentUserDislikedTask === 0) {
            $.ajax({
                url: '/task-like/addvote',
                type: 'POST',
                data: {'TaskVoteModel[taskId]': taskId,'TaskVoteModel[liked]': 1},
                success: function (response) {
                    taskSelector.attr('data-current-user-liked-task', 1);
                    amountLikes++;
                    likeSelector.text(amountLikes);
                }
            });
        }
        else if(currentUserLikedTask === 1 && currentUserDislikedTask === 0)
        {
            $.ajax({
                url: '/task-like/deletevote',
                type: 'POST',
                data: {taskId: taskId},
                success: function (response) {
                    taskSelector.attr('data-current-user-liked-task', 0);
                    amountLikes--;
                    likeSelector.text(amountLikes);
                }
            });
        }
        else if(currentUserLikedTask === 0 && currentUserDislikedTask === 1)
        {
            $.ajax({
                url: '/task-like/reversevote',
                type: 'POST',
                data: {taskId: taskId, like: 1},
                success: function (response) {

                    taskSelector.attr('data-current-user-liked-task', 1);
                    taskSelector.attr('data-current-user-disliked-task', 0);

                    amountLikes++;
                    likeSelector.text(amountLikes);
                    amountDislikes--;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
    });

    $('.task .vote-down i').click(function () {

        var dislikeSelector = $(this);
        var taskSelector = dislikeSelector.parents('.task');
        var likeSelector = taskSelector.find('.vote-up i');
        var amountLikes = likeSelector.text();
        var amountDislikes = dislikeSelector.text();
        var taskId = Number(taskSelector.attr('data-task-id'));
        var currentUserLikedTask = Number(taskSelector.attr('data-current-user-liked-task'));
        var currentUserDislikedTask = Number(taskSelector.attr('data-current-user-disliked-task'));

        if(currentUserLikedTask === 0 && currentUserDislikedTask === 0) {
            $.ajax({
                url: '/task-like/addvote',
                type: 'POST',
                data: {'TaskVoteModel[taskId]': taskId,'TaskVoteModel[liked]': 0},
                success: function (response) {
                    taskSelector.attr('data-current-user-disliked-task', 1);
                    amountDislikes++;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
        else if(currentUserLikedTask === 0 && currentUserDislikedTask === 1)
        {
            $.ajax({
                url: '/task-like/deletevote',
                type: 'POST',
                data: {taskId: taskId},
                success: function (response) {
                    taskSelector.attr('data-current-user-disliked-task', 0);
                    amountDislikes--;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
        else if(currentUserLikedTask === 1 && currentUserDislikedTask === 0)
        {
            $.ajax({
                url: '/task-like/reversevote',
                type: 'POST',
                data: {taskId: taskId, like: 0},
                success: function (response) {

                    taskSelector.attr('data-current-user-liked-task', 0);
                    taskSelector.attr('data-current-user-disliked-task', 1);

                    amountLikes--;
                    likeSelector.text(amountLikes);
                    amountDislikes++;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
    });
});