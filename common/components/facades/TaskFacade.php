<?php

namespace common\components\facades;


use common\models\entities\TaskEntity;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\task\TaskFileRepository;
use common\models\repositories\task\TaskRepository;
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
        NoticeRepository::instance()->saveNoticesForTask($task);

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

        TaskNoticesFacade::deleteNotices($task);

        if($task->isPrivate()){
            NoticeRepository::instance()->saveNoticesForPrivateTask($task);
        }
        else{
            NoticeRepository::instance()->saveNoticesForTask($task);
        }

        return $task;
    }

    /**
     * @param TaskEntity $task
     * @return TaskEntity
     * @throws \yii\db\Exception
     */
    public static function deleteTask(TaskEntity $task)
    {
        if($task->hasChildren()){
            foreach ($task->getChildren() as $child){
                TaskNoticesFacade::deleteNotices($child);
                TaskRepository::instance()->delete($child);
            }
        }

        TaskNoticesFacade::deleteNotices($task);

        return TaskRepository::instance()->delete($task);
    }
}