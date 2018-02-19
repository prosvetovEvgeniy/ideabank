<?php

namespace common\components\facades;

use common\models\entities\TaskEntity;
use common\models\repositories\task\TaskFileRepository;
use common\models\repositories\task\TaskRepository;
use yii\web\UploadedFile;

/**
 * Class TaskFacade
 * @package common\components\facades
 *
 * @property TaskNoticesFacade $taskNoticesFacade
 */
class TaskFacade
{
    private $taskNoticesFacade;

    /**
     * TaskFacade constructor.
     */
    public function __construct()
    {
        $this->taskNoticesFacade = new TaskNoticesFacade();
    }

    /**
     * @param TaskEntity $task
     * @param UploadedFile[] $files
     * @return TaskEntity
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function createTask(TaskEntity $task, array $files)
    {
        $task = TaskRepository::instance()->add($task);

        $this->taskNoticesFacade->saveNotices($task);

        TaskFileRepository::instance()->saveFiles($files, $task);

        return $task;
    }

    /**
     * @param TaskEntity $task
     * @param UploadedFile[] $files
     * @return TaskEntity
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function editTask(TaskEntity $task, array $files)
    {
        $task = TaskRepository::instance()->update($task);

        TaskFileRepository::instance()->saveFiles($files, $task);

        $this->taskNoticesFacade->deleteNotices($task);
        $this->taskNoticesFacade->saveNotices($task);

        return $task;
    }

    /**
     * @param TaskEntity $task
     * @return TaskEntity
     * @throws \yii\db\Exception
     */
    public function deleteTask(TaskEntity $task)
    {
        if ($task->hasChildren()) {
            foreach ($task->getChildren() as $child) {
                $this->taskNoticesFacade->deleteNotices($child);
                TaskRepository::instance()->delete($child);
            }
        }

        $this->taskNoticesFacade->deleteNotices($task);

        return TaskRepository::instance()->delete($task);
    }
}