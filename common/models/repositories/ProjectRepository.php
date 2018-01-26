<?php

namespace common\models\repositories;


use common\models\activerecords\Project;
use common\models\builders\ProjectEntityBuilder;
use common\models\entities\ProjectEntity;
use common\models\entities\UserEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class ProjectRepository
 * @package common\models\repositories
 *
 * @property ProjectEntityBuilder $builderBehavior
 */
class ProjectRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new ProjectEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return ProjectRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }


    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return ProjectEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Project::findOne($condition);

        if(!$model || $model->deleted)
        {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return ProjectEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Project::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function add(ProjectEntity $project)
    {
        $model = new Project();

        $this->builderBehavior->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save project with name = ' . $project->getName());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function update(ProjectEntity $project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        if(!$model)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update project with id = ' . $project->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function delete(ProjectEntity $project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        if(!$model)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete project with id = ' . $project->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Project::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param UserEntity $user
     * @return array
     */
    public function getProjectsForUser(UserEntity $user)
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects($user);

        if(!$participants)
        {
            return [];
        }

        $projects = [];

        foreach ($participants as $participant)
        {
            $projects[] = $participant->getProject();
        }

        return $projects;
    }
}