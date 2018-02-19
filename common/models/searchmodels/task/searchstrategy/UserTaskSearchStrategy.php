<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\searchmodels\task\TaskSearchForm;
use Yii;
use common\models\entities\TaskEntity;
use yii\base\NotSupportedException;

class UserTaskSearchStrategy implements ITaskSearchStrategy
{
    /**
     * @param string $status
     * @param int $projectId
     * @param string $title
     * @param string $content
     * @return array
     * @throws NotSupportedException
     */
    public function buildCondition(string $status, int $projectId, string $title, string $content)
    {
        $title = mb_strtolower($title);
        $content = mb_strtolower($content);

        //условия для отбрасывания чужых приватных комментариев
        $skipPrivate = [
            'or',
            ['not', ['visibility_area' => TaskEntity::VISIBILITY_AREA_PRIVATE]],
            [
                'and',
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_PRIVATE],
                ['author_id' => Yii::$app->user->identity->getId()]
            ]
        ];

        if ($status === TaskSearchForm::STATUS_ALL) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                $skipPrivate
            ];
        } else if ($status === TaskSearchForm::STATUS_COMPLETED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['status' => TaskEntity::STATUS_COMPLETED],
                $skipPrivate
            ];
        } else if ($status === TaskSearchForm::STATUS_NOT_COMPLETED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['deleted' => false],
                ['in', 'status', [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]],
                $skipPrivate
            ];
        } else if ($status === TaskSearchForm::STATUS_MERGED) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['status' => TaskEntity::STATUS_MERGED],
                ['deleted' => false],
                $skipPrivate
            ];
        } else if ($status === TaskSearchForm::STATUS_OWN) {
            return [
                'and',
                ['project_id' => $projectId],
                ['like', 'lower(title)', $title],
                ['like', 'lower(content)', $content],
                ['author_id' => Yii::$app->user->identity->getId()],
                ['deleted' => false]
            ];
        }

        throw new NotSupportedException();
    }
}