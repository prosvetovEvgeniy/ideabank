<?php

namespace common\models\interfaces;

use common\components\dataproviders\EntityDataProvider;
use yii\web\NotFoundHttpException;

interface EntitySearchInterface
{
    /**
     * @param array $params
     * @param int   $pageSize
     * @return EntityDataProvider
     * @throws NotFoundHttpException
     */
    public function search(array $params, int $pageSize);
}