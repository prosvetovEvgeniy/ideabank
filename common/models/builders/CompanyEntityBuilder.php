<?php

namespace common\models\builders;


use common\models\activerecords\Company;
use common\models\entities\CompanyEntity;

class CompanyEntityBuilder
{
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
        return new CompanyEntity($model->name, $model->id, $model->created_at, $model->updated_at, $model->deleted);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Company[] $models
     * @return CompanyEntity[]
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