<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\searchmodels\task\TaskEntitySearch;
use Yii;
use common\models\entities\TaskEntity;

class InvitedUserTaskSearchStrategy implements ITaskSearchStrategy
{
    public function buildCondition(string $status, int $projectId, string $title, string $content)
    {
        $title = mb_strtolower($title);
        $content = mb_strtolower($content);

        if($status === TaskEntitySearch::STATUS_ALL)
        {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL]
            ];
        }
        else if($status === TaskEntitySearch::STATUS_COMPLETED)
        {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['status' => TaskEntity::STATUS_COMPLETED],
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL]
            ];
        }
        else if($status === TaskEntitySearch::STATUS_NOT_COMPLETED)
        {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['in', 'status', [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]],
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL]
            ];
        }
        else if($status === TaskEntitySearch::STATUS_MERGED)
        {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['status' => TaskEntity::STATUS_MERGED],
                ['deleted' => false],
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL]
            ];
        }
        else if($status === TaskEntitySearch::STATUS_OWN)
        {
            return [
                'and',
                ['project_id' => $projectId],
                ['author_id' => -1]
            ];
        }
    }
}