<?php

namespace common\models\repositories\company;

use common\models\activerecords\Company;
use common\models\builders\CompanyEntityBuilder;
use common\models\entities\CompanyEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class CompanyRepository
 * @package common\models\repositories
 *
 * @property CompanyEntityBuilder $builderBehavior
 */
class CompanyRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new CompanyEntityBuilder();
    }


    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CompanyRepository
     */
    public static function instance(): IRepository
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

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return CompanyEntity|\common\models\interfaces\IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Company::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
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

        $this->builderBehavior->assignProperties($model, $company);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception("Cannot save company with name = " . $company->getName());
        }

        return $this->builderBehavior->buildEntity($model);
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

        $this->builderBehavior->assignProperties($model, $company);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update company with id = ' . $company->getId());
        }

        return $this->builderBehavior->buildEntity($model);
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

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Company::find()->where($condition)->count();
    }

    // #################### UNIQUE METHODS OF CLASS ######################



}