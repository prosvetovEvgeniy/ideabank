<?php

namespace common\models\builders;


use common\models\activerecords\TaskNotice;
use common\models\entities\TaskNoticeEntity;
use common\models\interfaces\INotice;

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
        return new TaskNoticeEntity($model->task_id, $model->notice_id, $model->id);
    }

    /**
     * @param TaskNotice[] $models
     * @return TaskNoticeEntity[]
     */
    public function buildEntities(array $models)
    {
        if(!$models)
        {
            return [];
        }

        $entities = [];

        foreach ($models as $model)
        {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}