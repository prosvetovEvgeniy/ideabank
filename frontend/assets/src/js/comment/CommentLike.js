
$( document ).ready(function() {

    $('.vote-up i').click(function () {

        var likeSelector = $(this);
        var comment = likeSelector.parents('.comment');
        var dislikeSelector = comment.find('.vote-down i');

        var commentId = Number(comment.attr('data-comment-id'));

        var amountLikes = likeSelector.text();

        var currentUserLikedIt = Number(comment.attr('data-current-user-liked-it'));
        var currentUserDislikedIt = Number(comment.attr('data-current-user-disliked-it'));

        if(currentUserLikedIt === 0 && currentUserDislikedIt === 0) {
            $.ajax({
                url: '/comment/addvote',
                type: 'POST',
                data: {commentId: commentId, like: true},
                success: function (response) {
                    comment.attr('data-current-user-liked-it', 1);
                    amountLikes++;
                    likeSelector.text(amountLikes);
                }
            });
        }
        else if(currentUserLikedIt === 1 && currentUserDislikedIt === 0)
        {
            $.ajax({
                url: '/comment/deletevote',
                type: 'POST',
                data: {commentId: commentId},
                success: function (response) {
                    comment.attr('data-current-user-liked-it', 0);
                    amountLikes--;
                    likeSelector.text(amountLikes);
                }
            });
        }
        else if(currentUserLikedIt === 0 && currentUserDislikedIt === 1)
        {
            $.ajax({
                url: '/comment/reversevote',
                type: 'POST',
                data: {commentId: commentId, like: true},
                success: function (response) {

                    comment.attr('data-current-user-liked-it', 1);
                    comment.attr('data-current-user-disliked-it', 0);

                    amountLikes++;

                    likeSelector.text(amountLikes);

                    var amountDislikes = dislikeSelector.text();
                    amountDislikes--;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
    });

    $('.vote-down i').click(function () {

        var dislikeSelector = $(this);
        var comment = dislikeSelector.parents('.comment');
        var likeSelector = comment.find('.vote-up i');

        var commentId = Number(comment.attr('data-comment-id'));

        var amountDislikes = dislikeSelector.text();
        var currentUserLikedIt = Number(comment.attr('data-current-user-liked-it'));
        var currentUserDislikedIt = Number(comment.attr('data-current-user-disliked-it'));

        if(currentUserLikedIt === 0 && currentUserDislikedIt === 0)
        {
            $.ajax({
                url: '/comment/addvote',
                type: 'POST',
                data: {commentId: commentId, like: false},
                success: function (response) {
                    comment.attr('data-current-user-disliked-it', 1);
                    amountDislikes++;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
        else if(currentUserLikedIt === 0 && currentUserDislikedIt === 1)
        {
            $.ajax({
                url: '/comment/deletevote',
                type: 'POST',
                data: {commentId: commentId},
                success: function (response) {
                    comment.attr('data-current-user-disliked-it', 0);
                    amountDislikes--;
                    dislikeSelector.text(amountDislikes);
                }
            });
        }
        else if(currentUserLikedIt === 1 && currentUserDislikedIt === 0)
        {
            $.ajax({
                url: '/comment/reversevote',
                type: 'POST',
                data: {commentId: commentId},
                success: function (response) {
                    comment.attr('data-current-user-liked-it', 0);
                    comment.attr('data-current-user-disliked-it', 1);

                    amountDislikes++;

                    dislikeSelector.text(amountDislikes);

                    var amountLikes = likeSelector.text();
                    amountLikes--;
                    likeSelector.text(amountLikes);
                }
            });
        }
    });
});