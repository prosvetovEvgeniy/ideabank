<?php

namespace common\components\facades;

use common\components\helpers\LinkHelper;
use common\components\helpers\NoticeHelper;
use common\models\entities\AuthAssignmentEntity;
use common\models\entities\CommentEntity;
use common\models\entities\CommentNoticeEntity;
use common\models\entities\NoticeEntity;
use common\models\repositories\notice\CommentNoticeRepository;
use common\models\repositories\notice\NoticeRepository;
use Yii;
use yii\db\Exception;

/**
 * Class CommentNoticeFacade
 * @package common\components\facades
 */
class CommentNoticeFacade
{

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public function saveNotices(CommentEntity $comment)
    {
        if ($comment->getPrivate()) {
            $this->savePrivateNotices($comment);
            return;
        }

        $noticeHelper = new NoticeHelper($comment->getContent());

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser)
        {
            $notice = NoticeRepository::instance()->add(
                new NoticeEntity(
                    $noticedUser->getId(),
                    $comment->getContent(),
                    LinkHelper::getLinkOnComment($comment, $noticedUser),
                    $comment->getSenderId()
                )
            );

            CommentNoticeRepository::instance()->add(
                new CommentNoticeEntity(
                    $comment->getId(),
                    $notice->getId()
                )
            );
        }
    }
    /**
     * @param CommentEntity $comment
     */
    public function deleteNotices(CommentEntity $comment)
    {
        $commentNotices = CommentNoticeRepository::instance()->deleteAll(['comment_id' => $comment->getId()]);
        NoticeRepository::instance()->deleteAll($commentNotices);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public function deleteAndSaveNotices(CommentEntity $comment)
    {
        $this->deleteNotices($comment);
        $this->saveNotices($comment);
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    private function savePrivateNotices(CommentEntity $comment)
    {
        $noticeHelper = new NoticeHelper($comment->getContent());

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser) {
            $isManager = Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $comment->getTask()->getProjectId(), $noticedUser->getId());

            if($comment->getSenderId() === $noticedUser->getId() || $isManager) {
                $notice = NoticeRepository::instance()->add(
                    new NoticeEntity(
                        $noticedUser->getId(),
                        $comment->getContent(),
                        LinkHelper::getLinkOnComment($comment, $noticedUser),
                        $comment->getSenderId()
                    )
                );

                CommentNoticeRepository::instance()->add(
                    new CommentNoticeEntity(
                        $comment->getId(),
                        $notice->getId()
                    )
                );
            }
        }
    }
}