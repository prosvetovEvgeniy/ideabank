<?php

namespace common\components\helpers;


use common\models\entities\ParticipantEntity;
use common\models\entities\ProjectEntity;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectsManagerRepository;
use common\models\repositories\project\ProjectRepository;
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
        if($projects === null) {
            $projects = ProjectRepository::instance()->getProjectsForUser();
        }

        return ArrayHelper::map($projects,
                                function(ProjectEntity $project){

                                    return $project->getId();
                                },
                                function(ProjectEntity $project) {

                                    return $project->getName();
                                }
                                );
    }

    /**
     * @return array
     */
    public static function getProjectForManagerItems()
    {
        $projects = ProjectsManagerRepository::instance()->findAll([], -1, -1);

        return ArrayHelper::map($projects,
                                function(ProjectEntity $project){
                                    return $project->getId();
                                },
                                function(ProjectEntity $project){
                                    return $project->getName();
                                });
    }
}