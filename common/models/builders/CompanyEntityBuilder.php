<?php

namespace common\models\builders;

use common\models\activerecords\Company;
use common\models\entities\CompanyEntity;

/**
 * Class CompanyEntityBuilder
 * @package common\models\builders
 *
 * @property ParticipantEntityBuilder $participantEntityBuilder
 * @property ProjectEntityBuilder     $projectEntityBuilder
 */
class CompanyEntityBuilder
{
    private $participantEntityBuilder;
    private $projectEntityBuilder;

    public function __construct()
    {
        $this->participantEntityBuilder = new ParticipantEntityBuilder();
        $this->projectEntityBuilder = new ProjectEntityBuilder();
    }

    /**
     * @return CompanyEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Company $model
     * @param CompanyEntity $company
     */
    public function assignProperties(&$model, &$company)
    {
        $model->name = $company->getName();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Company $model
     * @return CompanyEntity
     */
    public function buildEntity(Company $model)
    {
        $projects = null;
        $participants = null;

        if ($model->isRelationPopulated('projects')) {
            $projects = ($model->projects) ? ProjectEntityBuilder::instance()->buildEntities($model->projects) : null;
        }

        if ($model->isRelationPopulated('participants')) {
            $participants = ($model->participants) ? ParticipantEntityBuilder::instance()->buildEntities($model->participants) : null;
        }

        return new CompanyEntity(
            $model->name, 
            $model->id, 
            $model->created_at, 
            $model->updated_at, 
            $model->deleted,
            $projects,
            $participants
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Company[] $models
     * @return CompanyEntity[]
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