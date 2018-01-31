<?php

namespace common\models\searchmodels\task\searchstrategy;

use common\models\entities\ProjectEntity;

interface ITaskSearchStrategy
{
    /**
     * @param string $status
     * @param string $title
     * @param string $content
     * @param int $projectId
     * @return array
     */
    public function buildCondition(string $status, int $projectId, string $title, string $content);
}