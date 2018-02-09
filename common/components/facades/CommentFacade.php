<?php

namespace common\components\facades;


use common\components\helpers\LinkHelper;
use common\models\entities\CommentEntity;
use common\models\repositories\CommentNoticeRepository;
use common\models\repositories\CommentRepository;
use common\models\repositories\NoticeRepository;
use yii\db\Exception;

class CommentFacade
{
    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public static function createComment(CommentEntity $comment)
    {
        $comment = CommentRepository::instance()->add($comment);

        self::saveNotices($comment);

        return $comment;
    }


    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws \yii\db\Exception
     */
    public static function editComment(CommentEntity $comment)
    {
        CommentRepository::instance()->update($comment);

        self::clearAndSaveNotices($comment);
        
        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws \yii\db\Exception
     */
    public static function deleteComment(CommentEntity $comment)
    {
        self::deleteNotices($comment);

        return CommentRepository::instance()->delete($comment);
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public static function reestablishComment(CommentEntity $comment)
    {
        $comment->setDeleted(false);
        CommentRepository::instance()->update($comment);

        self::saveNotices($comment);

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

        self::deleteNotices($comment);

        NoticeRepository::instance()->saveNoticesForPrivateComment($comment);

        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public static function makePublic(CommentEntity $comment)
    {
        $comment->setPrivate(false);
        CommentRepository::instance()->update($comment);

        self::clearAndSaveNotices($comment);

        return $comment;
    }


    //################# PRIVATE METHODS #################


    /**
     * @param CommentEntity $comment
     */
    private static function deleteNotices(CommentEntity $comment)
    {
        $commentNotices = CommentNoticeRepository::instance()->deleteAll(['comment_id' => $comment->getId()]);
        NoticeRepository::instance()->deleteAll($commentNotices);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    private static function saveNotices(CommentEntity $comment)
    {
        NoticeRepository::instance()->saveNoticesForComment($comment);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    private static function clearAndSaveNotices(CommentEntity $comment)
    {
        self::deleteNotices($comment);
        self::saveNotices($comment);
    }
}