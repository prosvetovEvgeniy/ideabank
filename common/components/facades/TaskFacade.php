<?php

namespace common\components\facades;

use common\models\entities\TaskEntity;
use common\models\repositories\task\TaskFileRepository;
use common\models\repositories\task\TaskRepository;
use yii\web\UploadedFile;
use yii\db\Exception;

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
     * @param array $files
     * @return TaskEntity
     * @throws Exception
     * @throws \yii\base\Exception
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
     * @param array $files
     * @return TaskEntity
     * @throws Exception
     * @throws \yii\base\Exception
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
     * @throws Exception
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