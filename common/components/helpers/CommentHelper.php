<?php

namespace common\components\helpers;


use common\models\activerecords\CommentView;
use common\models\entities\AuthAssignmentEntity;
use common\models\entities\CommentEntity;
use Yii;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;

class CommentHelper
{
    /**
     * Расчитывает позицию нового комментария
     * для определенног пользователя (в зависимости от роли)
     *
     * @param CommentEntity $comment
     * @param UserEntity $user
     * @return int
     */
    public static function getNewCommentIndex(CommentEntity $comment, UserEntity $user)
    {
        $models = CommentView::find()->where([
            'and',
            ['task_id' => $comment->getTaskId()],
            ['<=', 'id', $comment->getId()],
            ['<=', 'created_at', $comment->getCreatedAt()]
        ]);

        if(!Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $comment->getTask()->getProjectId(), $user->getId())) {
            $models = $models->andWhere([
                'or',
                ['private' => false],
                ['sender_id' => $user->getId()]
            ]);
        }

        return (int) $models->count();
    }
}