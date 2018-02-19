<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\searchmodels\task\TaskSearchForm;
use Yii;
use common\models\entities\TaskEntity;

/**
 * Class ManagerTaskSearchStrategy
 * @package common\models\searchmodels\task\searchstrategy
 */
class ManagerTaskSearchStrategy implements ITaskSearchStrategy
{
    public function buildCondition(TaskEntity $task)
    {
        $title = mb_strtolower($task->getTitle());
        $content = mb_strtolower($task->getContent());

        if($task->getStatus() === TaskSearchForm::STATUS_ALL) {
            return [
                'and',
                ['project_id' => $task->getProjectId()],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
            ];
        } else if($task->getStatus() === TaskSearchForm::STATUS_COMPLETED) {
            return [
                'and',
                ['project_id' => $task->getProjectId()],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['status' => TaskEntity::STATUS_COMPLETED],
            ];
        } else if($task->getStatus() === TaskSearchForm::STATUS_NOT_COMPLETED) {
            return [
                'and',
                ['project_id' => $task->getProjectId()],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['in', 'status', [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]],
            ];
        } else if($task->getStatus() === TaskSearchForm::STATUS_MERGED) {
            return [
                'and',
                ['project_id' => $task->getProjectId()],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['status' => TaskEntity::STATUS_MERGED],
                ['deleted' => false],
            ];
        } else if($task->getStatus() === TaskSearchForm::STATUS_OWN) {
            return [
                'and',
                ['project_id' => $task->getProjectId()],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['author_id' => Yii::$app->user->identity->getUserId()],
                ['deleted' => false]
            ];
        }
    }
}