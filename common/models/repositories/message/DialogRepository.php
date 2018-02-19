<?php

namespace common\models\repositories\message;

use common\models\builders\MessageEntityBuilder;
use common\models\entities\MessageEntity;
use common\models\activerecords\Message;
use common\models\interfaces\IRepository;

/**
 * Class DialogRepository
 * @package common\models\repositories
 *
 * @property MessageEntityBuilder $builderBehavior
 */
class DialogRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new MessageEntityBuilder();
    }

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return DialogRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string $orderBy
     * @return MessageEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $dialogIds = Message::find()->select('MAX(id) as id')
                                    ->where($condition )
                                    ->offset($offset)
                                    ->limit($limit)
                                    ->groupBy('companion_id')
                                    ->all();

        $models = Message::find()->with('companion')
                                 ->where(['in', 'id', $dialogIds])
                                 ->orderBy($orderBy)
                                 ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Message::find()->select('MAX(id) as id')
                                    ->where($condition )
                                    ->groupBy('companion_id')
                                    ->count();
    }
}