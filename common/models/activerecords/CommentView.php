<?php

namespace common\models\activerecords;


/**
 * @property integer $id
 * @property integer $task_id
 * @property integer $sender_id
 * @property string $content
 * @property integer $parent_id
 * @property boolean $private
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $deleted
 *
 * @property Comment $comment
 * @property Comment[] $comments
 * @property Task $task
 * @property Users $user
 * @property CommentLike[] $commentLikes
 *
 * @property int $likes_amount
 * @property int $dislikes_amount
 * @property bool $current_user_liked_it
 * @property bool $current_user_disliked_it
 */
class CommentView extends Comment
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'likes_amount',
            'dislikes_amount',
            'current_user_liked_it',
            'current_user_disliked_it'
        ]);
    }
}