<?php

namespace common\components\managers;

use common\models\Company;
use yii\db\Exception;

class CompanyManager
{
    /**
     * @param $name
     * @return Company
     * @throws Exception
     */
    public function createCompany($name)
    {
        $company = new Company();
        $company->name = $name;

        if(!$company->save())
        {
            throw new Exception('Cannot save company to the database');
        }

        return $company;
    }
}