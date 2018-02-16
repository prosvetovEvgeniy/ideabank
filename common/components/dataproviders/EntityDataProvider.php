<?php

namespace common\components\dataproviders;


use common\models\interfaces\IRepository;
use yii\data\BaseDataProvider;

/**
 * Class EntityDataProvider
 * @package common\components\dataproviders
 *
 * @property array       $condition
 * @property IRepository $repositoryInstance
 * @property string      $orderBy
 */
class EntityDataProvider extends BaseDataProvider
{
    public $condition;
    public $repositoryInstance;
    public $orderBy = 'id ASC';

    /**
     * @return array|\common\models\interfaces\IEntity[]
     */
    public function prepareModels()
    {
        $pagination = $this->getPagination();

        if($pagination !== false) {
            $pagination->totalCount = $this->getTotalCount();

            if($pagination->totalCount === 0) {
                return [];
            }
        }

        return $this->repositoryInstance->findAll($this->condition,
                                                  $pagination->getLimit(),
                                                  $pagination->getOffset(),
                                                  $this->orderBy);

    }

    /**
     * @return int|string
     */
    public function prepareTotalCount()
    {
        return $this->repositoryInstance->getTotalCountByCondition($this->condition);
    }

    /**
     * @param array $models
     * @return array|void
     */
    public function prepareKeys($models)
    {
        //throw new NotSupportedException();
    }
}