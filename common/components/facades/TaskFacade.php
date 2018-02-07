<?php

namespace common\components\facades;


use common\components\helpers\LinkHelper;
use common\models\entities\TaskEntity;
use common\models\repositories\NoticeRepository;
use common\models\repositories\TaskFileRepository;
use common\models\repositories\TaskNoticeRepository;
use common\models\repositories\TaskRepository;
use yii\web\UploadedFile;

class TaskFacade
{
    /**
     * @param TaskEntity $task
     * @param UploadedFile[] $files
     * @return TaskEntity
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function createTask(TaskEntity $task, array $files)
    {
        $task = TaskRepository::instance()->add($task);
        TaskFileRepository::instance()->saveFiles($files, $task);
        NoticeRepository::instance()->saveNoticesForTask($task, LinkHelper::getLinkOnTask($task));

        return $task;
    }

    /**
     * @param TaskEntity $task
     * @param UploadedFile[] $files
     * @return TaskEntity
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function editTask(TaskEntity $task, array $files)
    {
        $task = TaskRepository::instance()->update($task);

        //если есть файлы, то сохраняем их
        TaskFileRepository::instance()->saveFiles($files, $task);

        //если заметки были, то очищаем их
        $taskNotices = TaskNoticeRepository::instance()->findAll(['task_id' => $task->getId()]);
        TaskNoticeRepository::instance()->deleteAll($taskNotices);
        NoticeRepository::instance()->deleteAll($taskNotices);

        //и создаем новые
        NoticeRepository::instance()->saveNoticesForTask($task, LinkHelper::getLinkOnTask($task));

        return $task;
    }
}