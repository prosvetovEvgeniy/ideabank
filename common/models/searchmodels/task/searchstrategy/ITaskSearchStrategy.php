<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\entities\TaskEntity;

interface ITaskSearchStrategy
{
    /**
     * @param TaskEntity $task
     * @return mixed
     */
    public function buildCondition(TaskEntity $task);
}