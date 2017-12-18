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
     * @return CompanyEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Company::findOne($condition);

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
     * @return CompanyEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null)
    {
        $models = Company::find()->where($condition)->offset($offset)->limit($limit)->all();

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