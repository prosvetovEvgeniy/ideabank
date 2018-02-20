<?php

namespace common\models\repositories\participant;

use common\models\activerecords\Participant;
use common\models\builders\ParticipantEntityBuilder;
use common\models\entities\ParticipantEntity;
use common\models\interfaces\IRepository;
use common\models\interfaces\IEntity;

/**
 * Class ParticipantViewRepository
 * @package common\models\repositories\participant
 *
 * @property ParticipantEntityBuilder $builderBehavior
 */
class ParticipantViewRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new ParticipantEntityBuilder();
    }

    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return ParticipantEntity[]|IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Participant::find()->leftJoin('users', 'participant.user_id = users.id')
                                     ->leftJoin('auth_assignment', 'auth_assignment.user_id = participant.id')
                                     ->where($condition)
                                     ->with('user')
                                     ->with('project')
                                     ->with('authAssignment')
                                     ->offset($offset)
                                     ->limit($limit)
                                     ->orderBy($orderBy)
                                     ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Participant::find()->leftJoin('users', 'participant.user_id = users.id')
                                        ->leftJoin('auth_assignment', 'auth_assignment.user_id = participant.id')
                                        ->where($condition)
                                        ->count();
    }
}