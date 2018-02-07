<?php

namespace common\components\helpers;


use common\models\entities\ProjectEntity;
use common\models\repositories\ProjectRepository;
use yii\helpers\ArrayHelper;

class ProjectHelper
{
    /**
     * Возвращает ассоциативный массив типа [$projectId => $projectName]
     *
     * @param ProjectEntity[] $projects
     * @return array
     */
    public static function getProjectItems(array $projects = null)
    {
        if($projects === null)
        {
            $projects = ProjectRepository::instance()->getProjectsForUser();
        }

        return ArrayHelper::map($projects,
            function($project){
                /**
                 * @var ProjectEntity $project
                 */
                return $project->getId();
            },
            function($project) {
                /**
                 * @var ProjectEntity $project
                 */
                return $project->getName();
            }
            );
    }
}