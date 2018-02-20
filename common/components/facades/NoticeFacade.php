<?php

namespace common\components\facades;

use common\models\entities\NoticeEntity;
use common\models\repositories\notice\CommentNoticeRepository;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\notice\TaskNoticeRepository;
use yii\db\Exception;

class NoticeFacade
{
    /**
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     * @throws \Throwable
     */
    public function deleteNotice(NoticeEntity $notice)
    {
        $taskNotice = TaskNoticeRepository::instance()->findOne(['notice_id' => $notice->getId()]);

        if ($taskNotice) {
            TaskNoticeRepository::instance()->delete($taskNotice);
            return NoticeRepository::instance()->delete($taskNotice);
        }

        $commentNotice = CommentNoticeRepository::instance()->findOne(['notice_id' => $notice->getId()]);

        if ($commentNotice) {
            CommentNoticeRepository::instance()->delete($commentNotice);
            return NoticeRepository::instance()->delete($commentNotice);
        }

        return NoticeRepository::instance()->delete($notice);
    }
}