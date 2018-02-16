<?php

namespace common\components\helpers;


use common\models\interfaces\IEntity;
use yii\helpers\ArrayHelper;

class EntityHelper
{
    /**
     * @param array $entities
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function map(array $entities, string $key, string $value)
    {
        $map = [];

        foreach ($entities as $entity){
            $map[$entity->$key()] = $entity->$value();
        }

        return $map;
    }
}