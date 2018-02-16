<?php

namespace common\tests\models\repositories;


use common\models\entities\CompanyEntity;
use common\models\repositories\company\CompanyRepository;

class CompanyRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'name'      => 'testName',
    ];

    /** @var array */
    protected $dataForSetters = [
        'name'      => 'newName'
    ];

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $companyRepository = CompanyRepository::instance();

        $this->assertEquals($companyRepository, new CompanyRepository());
    }

    public function testAdd()
    {
        /** @var CompanyEntity $company */
        $company = CompanyRepository::instance()->add(new CompanyEntity($this->data['name']));

        $this->tester->seeRecord($this->paths['company'], ['id' => $company->getId()]);
    }

    public function testUpdate()
    {
        /** @var CompanyEntity $company */
        $company = CompanyRepository::instance()->add(new CompanyEntity($this->data['name']));

        $company->setName($this->dataForSetters['name']);

        $this->assertEquals(CompanyRepository::instance()->update($company), $company);
    }

    public function testDelete()
    {
        /** @var CompanyEntity $company */
        $company = CompanyRepository::instance()->add(new CompanyEntity($this->data['name']));

        CompanyRepository::instance()->delete($company);

        $this->tester->seeRecord($this->paths['company'], ['id' => $company->getId(), 'deleted' => true]);
    }
}