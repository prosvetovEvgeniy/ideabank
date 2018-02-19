<?php

namespace common\models\interfaces;

use common\components\dataproviders\EntityDataProvider;

interface ISearchEntityModel
{
    /**
     * @param int $pageSize
     * @return EntityDataProvider
     */
    public function search(int $pageSize = 20): EntityDataProvider;
}