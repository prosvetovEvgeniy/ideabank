<?php

namespace common\components\helpers;

use common\models\entities\TaskEntity;
use common\models\repositories\task\TaskRepository;
use yii\helpers\ArrayHelper;

class TaskHelper
{
    /**
     * Возвращает задачи, которые могут стать родительскими
     * для определенной задачи
     *
     * @param TaskEntity $task
     * @return array
     */
    public static function getParentTasksItems(TaskEntity $task)
    {
        $tasks = TaskRepository::instance()->findAll([
            'and',
            ['project_id' => $task->getProjectId()],
            ['not', ['id' => $task->getId()]],
            ['not', ['status' => TaskEntity::STATUS_MERGED]],
            ['deleted' => false]
        ], -1, -1, 'id ASC');

        return ArrayHelper::map($tasks,
                                function (TaskEntity $task){
                                    return $task->getId();
                                },
                                function (TaskEntity $task){
                                    return $task->getTitle();
                                });
    }
}