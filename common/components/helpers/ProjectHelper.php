<?php

namespace common\components\helpers;


use common\models\activerecords\Project;
use common\models\activerecords\Task;

class ProjectHelper
{
    /**
     * @param Project $project
     * @return int|string
     */
    public static function getTasksCount($project)
    {
        return Task::find()->where(['project_id' => $project->id])->count();
    }
}