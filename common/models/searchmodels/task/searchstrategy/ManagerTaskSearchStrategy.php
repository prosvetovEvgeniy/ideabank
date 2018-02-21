<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\searchmodels\task\TaskSearchForm;
use Yii;
use common\models\entities\TaskEntity;
use yii\base\NotSupportedException;

class ManagerTaskSearchStrategy implements ITaskSearchStrategy
{
    public function buildCondition(string $status, int $projectId, string $title, string $content)
    {
        $title = mb_strtolower($title);
        $content = mb_strtolower($content);

        if ($status === TaskSearchForm::STATUS_ALL) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
            ];
        } else if ($status === TaskSearchForm::STATUS_COMPLETED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['status' => TaskEntity::STATUS_COMPLETED],
            ];
        } else if ($status === TaskSearchForm::STATUS_NOT_COMPLETED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['in', 'status', [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]],
            ];
        } else if ($status === TaskSearchForm::STATUS_MERGED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['status' => TaskEntity::STATUS_MERGED],
                ['deleted' => false],
            ];
        } else if ($status === TaskSearchForm::STATUS_OWN) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['author_id' => Yii::$app->user->getId()],
                ['deleted' => false]
            ];
        } else {
            throw new NotSupportedException();
        }
    }
}