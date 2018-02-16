<?php

namespace common\components\facades;


use common\models\entities\CommentEntity;
use common\models\repositories\comment\CommentRepository;
use common\models\repositories\notice\NoticeRepository;
use yii\db\Exception;

class CommentFacade
{
    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     * @throws \Exception
     */
    public static function createComment(CommentEntity $comment)
    {
        $comment = CommentRepository::instance()->add($comment);

        CommentNoticeFacade::saveNotices($comment);

        return $comment;
    }


    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     * @throws \Exception
     */
    public static function editComment(CommentEntity $comment)
    {
        CommentRepository::instance()->update($comment);
        CommentNoticeFacade::deleteAndSaveNotices($comment);
        
        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws \yii\db\Exception
     */
    public static function deleteComment(CommentEntity $comment)
    {
        CommentNoticeFacade::deleteNotices($comment);

        return CommentRepository::instance()->delete($comment);
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     * @throws \Exception
     */
    public static function reestablishComment(CommentEntity $comment)
    {
        $comment->setDeleted(false);
        CommentRepository::instance()->update($comment);

        CommentNoticeFacade::saveNotices($comment);

        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public static function makePrivate(CommentEntity $comment)
    {
        $comment->setPrivate(true);
        CommentRepository::instance()->update($comment);

        CommentNoticeFacade::deleteNotices($comment);

        NoticeRepository::instance()->saveNoticesForPrivateComment($comment);

        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     * @throws \Exception
     */
    public static function makePublic(CommentEntity $comment)
    {
        $comment->setPrivate(false);
        CommentRepository::instance()->update($comment);

        CommentNoticeFacade::deleteAndSaveNotices($comment);

        return $comment;
    }
}