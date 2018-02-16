<?php

namespace common\components\facades;


use common\models\entities\CommentEntity;
use common\models\repositories\notice\CommentNoticeRepository;
use common\models\repositories\notice\NoticeRepository;
use Exception;

class CommentNoticeFacade
{
    /**
     * @param CommentEntity $comment
     */
    public static function deleteNotices(CommentEntity $comment)
    {
        $commentNotices = CommentNoticeRepository::instance()->deleteAll(['comment_id' => $comment->getId()]);
        NoticeRepository::instance()->deleteAll($commentNotices);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public static function saveNotices(CommentEntity $comment)
    {
        NoticeRepository::instance()->saveNoticesForComment($comment);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public static function deleteAndSaveNotices(CommentEntity $comment)
    {
        self::deleteNotices($comment);
        self::saveNotices($comment);
    }
}