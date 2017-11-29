<?php

namespace common\models\repositories;

use common\models\activerecords\Company;
use common\models\entities\CompanyEntity;
use yii\db\Exception;
use Yii;

class CompanyRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CompanyRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return CompanyEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Company::findOne($condition);

        if(!$model)
        {
            throw new Exception('Company with ' . json_encode($condition) . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Company with ' . json_encode($condition) . ' already deleted');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return CompanyEntity[]
     */
    public function findAll(array $condition)
    {
        $models = Company::findAll($condition);

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param CompanyEntity $company
     * @return CompanyEntity
     * @throws Exception
     */
    public function add(CompanyEntity $company)
    {
        $model = new Company();

        $this->assignProperties($model, $company);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception("Cannot save company with name = " . $company->getName());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param CompanyEntity $company
     * @return CompanyEntity
     * @throws Exception
     */
    public function update(CompanyEntity $company)
    {
        $model = Company::findOne(['id' => $company->getId()]);

        if(!$model)
        {
            throw new Exception('Company with id = ' . $company->getId() . ' does not exists');
        }

        $this->assignProperties($model, $company);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update company with id = ' . $company->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param CompanyEntity $company
     * @return CompanyEntity
     * @throws Exception
     */
    public function delete(CompanyEntity $company)
    {
        $model = Company::findOne(['id' => $company->getId()]);

        if(!$model)
        {
            throw new Exception('Company with id = ' . $company->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Company with id = ' . $company->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete company with id = ' . $company->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Company $model
     * @param CompanyEntity $company
     */
    protected function assignProperties(&$model, &$company)
    {
        $model->name = $company->getName();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Company $model
     * @return CompanyEntity
     */
    protected function buildEntity(Company $model)
    {
        return new CompanyEntity($model->name, $model->id, $model->created_at, $model->updated_at, $model->deleted);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Company[] $models
     * @return CompanyEntity[]
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



}