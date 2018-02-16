<?php

namespace common\components\facades;


use common\models\entities\TaskEntity;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\notice\TaskNoticeRepository;

class TaskNoticesFacade
{
    /**
     * @param TaskEntity $task
     */
    public static function deleteNotices(TaskEntity $task)
    {
        $taskNotices = TaskNoticeRepository::instance()->deleteAll(['task_id' => $task->getId()]);
        NoticeRepository::instance()->deleteAll($taskNotices);
    }
}