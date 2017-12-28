<?php

namespace common\models\repositories;


use common\models\activerecords\Project;
use common\models\entities\ProjectEntity;
use common\models\entities\UserEntity;
use yii\db\Exception;
use Yii;

class ProjectRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return ProjectRepository
     */
    public static function instance()
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

        return $this->buildEntity($model);
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

        return $this->buildEntities($models);
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

        $this->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save project with name = ' . $project->getName());
        }

        return $this->buildEntity($model);
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

        $this->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update project with id = ' . $project->getId());
        }

        return $this->buildEntity($model);
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

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Project $model
     * @param ProjectEntity $project
     */
    protected function assignProperties(&$model, &$project)
    {
        $model->name = $project->getName();
        $model->company_id = $project->getCompanyId();
        $model->default_visibility_area = $project->getDefaultVisibilityArea();
    }

    /**
     * @param Project $model
     * @return ProjectEntity
     */
    public function buildEntity(Project $model)
    {
        return new ProjectEntity($model->name,$model->company_id, $model->default_visibility_area,
                                 $model->id, $model->created_at, $model->updated_at, $model->deleted);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Project[] $models
     * @return ProjectEntity[]
     */
    protected function buildEntities(array $models)
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