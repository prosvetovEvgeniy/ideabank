<?php

namespace common\components\dataproviders;


use yii\data\BaseDataProvider;

class EntityDataProvider extends BaseDataProvider
{
    /** @var array */
    public $condition;

    public $repositoryInstance;

    public function prepareModels()
    {
        if(empty($this->condition))
        {
            return [];
        }

        $pagination = $this->getPagination();

        if($pagination !== false)
        {
            $pagination->totalCount = $this->getTotalCount();

            if($pagination->totalCount === 0)
            {
                return [];
            }

            return $this->repositoryInstance->findAll($this->condition, $pagination->getLimit(), $pagination->getOffset());
        }
    }

    public function prepareTotalCount()
    {
        return $this->repositoryInstance->getTotalCountByCondition($this->condition);
    }

    public function prepareKeys($models)
    {

    }
}