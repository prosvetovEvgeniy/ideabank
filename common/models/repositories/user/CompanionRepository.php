<?php

namespace common\models\repositories\user;

use common\models\activerecords\Message;
use common\models\activerecords\Users;
use common\models\builders\UserEntityBuilder;
use common\models\entities\UserEntity;
use common\models\interfaces\IEntity;
use common\models\interfaces\IRepository;
use yii\helpers\ArrayHelper;

/**
 * Class CompanionRepository
 * @package common\models\repositories
 *
 * @property UserEntityBuilder $builderBehavior
 */
class CompanionRepository implements IRepository
{
    public $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new UserEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return CompanionRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return UserEntity[]|IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $companionIds = Message::find()->select('companion_id')
                                       ->where($condition )
                                       ->distinct('companion_id')
                                       ->offset($offset)
                                       ->limit($limit)
                                       ->orderBy($orderBy)
                                       ->all();

        $models = Users::find()->where(['in', 'id', ArrayHelper::getColumn($companionIds,'companion_id')])->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Message::find()->select('companion_id')
                                    ->where($condition )
                                    ->distinct('companion_id')
                                    ->count();
    }
}