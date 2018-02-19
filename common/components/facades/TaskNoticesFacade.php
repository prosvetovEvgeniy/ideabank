<?php

namespace common\components\facades;


use common\components\helpers\LinkHelper;
use common\components\helpers\NoticeHelper;
use common\models\entities\AuthAssignmentEntity;
use common\models\entities\NoticeEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskNoticeEntity;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\notice\TaskNoticeRepository;
use Yii;

/**
 * Class TaskNoticesFacade
 * @package common\components\facades
 */
class TaskNoticesFacade
{
    /**
     * @param TaskEntity $task
     * @throws \yii\db\Exception
     */
    public function saveNotices(TaskEntity $task)
    {
        //если задача приватная
        if($task->isPrivate()){
            $this->savePrivateNotices($task);
            return;
        }

        $noticeHelper = new NoticeHelper($task->getContent());
        $link = LinkHelper::getLinkOnTask($task);

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser) {

            $notice = NoticeRepository::instance()->add(
                new NoticeEntity(
                    $noticedUser->getId(),
                    $task->getContent(),
                    $link,
                    $task->getAuthorId()
                )
            );

            TaskNoticeRepository::instance()->add(
                new TaskNoticeEntity(
                    $task->getId(),
                    $notice->getId()
                )
            );
        }
    }

    /**
     * @param TaskEntity $task
     * @throws \yii\db\Exception
     */
    private function savePrivateNotices(TaskEntity $task)
    {
        $noticeHelper = new NoticeHelper($task->getContent());

        $link = LinkHelper::getLinkOnTask($task);

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser) {

            $isManager = Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $task->getProjectId(), $noticedUser->getId());

            if($task->getAuthorId() === Yii::$app->user->identity->getUserId() || $isManager){

                $notice = NoticeRepository::instance()->add(
                    new NoticeEntity(
                        $noticedUser->getId(),
                        $task->getContent(),
                        $link,
                        $task->getAuthorId()
                    )
                );

                TaskNoticeRepository::instance()->add(
                    new TaskNoticeEntity(
                        $task->getId(),
                        $notice->getId()
                    )
                );
            }
        }
    }

    /**
     * @param TaskEntity $task
     */
    public function deleteNotices(TaskEntity $task)
    {
        $taskNotices = TaskNoticeRepository::instance()->deleteAll(['task_id' => $task->getId()]);
        NoticeRepository::instance()->deleteAll($taskNotices);
    }
}