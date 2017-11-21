<?php

namespace common\models\repositories;

use common\models\activerecords\Company;
use common\models\entities\CompanyEntity;
use yii\db\Exception;
use Yii;

class CompanyRepository
{
    /**
     * @return CompanyRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * @param integer $id
     * @return CompanyEntity
     * @throws Exception
     */
    public function get($id)
    {
        $model = Company::findOne(['id' => $id]);

        if(!$model)
        {
            throw new Exception('Company with id = ' . $id . ' doesn\'t exists');
        }

        return $this->buildEntity($model);
    }

    /**
     * @param CompanyEntity $company
     * @throws Exception
     */
    public function add($company)
    {
        $model = new Company();

        $model->name = $company->getName();

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save company with name = ' . $company->getName());
        }
    }

    /**
     * @param CompanyEntity $company
     * @throws Exception
     */
    public function update($company)
    {
        $model = Company::findOne(['id' => $company->getId()]);

        $model->id = $company->getId();
        $model->name = $company->getName();

        if(!$model->update())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update company with id = ' . $company->getId());
        }
    }

    /**
     * @param CompanyEntity $company
     * @throws Exception
     */
    public function delete($company)
    {
        $model = Company::findOne(['id' => $company->getId()]);

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete company with id = ' . $company->getId());
        }
    }

    /**
     * @param Company $model
     * @return CompanyEntity
     */
    protected function buildEntity($model)
    {
        return new CompanyEntity($model->name, $model->id, $model->created_at, $model->updated_at);
    }
}