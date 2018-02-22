<?php

namespace common\models\builders;

use common\models\activerecords\TaskNotice;
use common\models\entities\TaskNoticeEntity;

/**
 * Class TaskNoticeBuilder
 * @package common\models\builders
 */
class TaskNoticeBuilder
{
    /**
     * @return TaskNoticeBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * @param TaskNotice $model
     * @param TaskNoticeEntity $taskNotice
     */
    public function assignProperties(TaskNotice &$model, TaskNoticeEntity &$taskNotice)
    {
        $model->task_id = $taskNotice->getTaskId();
        $model->notice_id = $taskNotice->getNoticeId();
    }

    /**
     * @param TaskNotice $model
     * @return TaskNoticeEntity
     */
    public function buildEntity(TaskNotice $model)
    {
        $notice = null;
        $task = null;

        if ($model->isRelationPopulated('notice')) {
            $notice = ($model->notice) ? NoticeEntityBuilder::instance()->buildEntity($model->notice) : null;
        }

        if ($model->isRelationPopulated('task')) {
            $task = ($model->task) ? TaskEntityBuilder::instance()->buildEntity($model->task) : null;
        }

        return new TaskNoticeEntity(
            $model->task_id, 
            $model->notice_id, 
            $model->id,
            $task,
            $notice
        );
    }

    /**
     * @param TaskNotice[] $models
     * @return TaskNoticeEntity[]
     */
    public function buildEntities(array $models)
    {
        if (!$models) {
            return [];
        }

        $entities = [];

        foreach ($models as $model) {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}