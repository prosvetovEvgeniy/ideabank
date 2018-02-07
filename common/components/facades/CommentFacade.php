<?php

namespace common\components\facades;


use common\components\helpers\LinkHelper;
use common\models\entities\CommentEntity;
use common\models\repositories\CommentRepository;
use common\models\repositories\NoticeRepository;

class CommentFacade
{
    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws \yii\db\Exception
     */
    public static function createComment(CommentEntity $comment)
    {
        $comment = CommentRepository::instance()->add($comment);

        NoticeRepository::instance()->saveNoticesForComment(
            $comment,
            LinkHelper::getLinkOnComment($comment)
        );

        return $comment;
    }
}