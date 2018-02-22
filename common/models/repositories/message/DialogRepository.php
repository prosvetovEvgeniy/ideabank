<?php

namespace common\models\repositories\message;

use common\models\builders\MessageEntityBuilder;
use common\models\activerecords\Message;
use common\models\interfaces\IRepository;
use yii\helpers\ArrayHelper;

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
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param array $with
     * @return MessageRepository[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    )
    {
        $lastMessages = Message::find()->select('MAX(id) as id')
                                       ->where($condition)
                                       ->groupBy('companion_id')
                                       ->limit($limit)
                                       ->offset($offset)
                                       ->all();

        $ids = ArrayHelper::getColumn($lastMessages, function (Message $message){
            return $message->id;
        });

        $models = Message::find()->where(['in', 'id', $ids])
                                 ->with($with)
                                 ->limit($limit)
                                 ->offset($offset)
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