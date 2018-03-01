<?php

namespace common\models\repositories\participant;

use common\models\activerecords\Participant;
use common\models\builders\ParticipantEntityBuilder;
use common\models\entities\ParticipantEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class ParticipantRepository
 * @package common\models\repositories
 *
 * @property ParticipantEntityBuilder $builderBehavior
 */
class ParticipantRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new ParticipantEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return ParticipantRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @param array $with
     * @return ParticipantEntity|null
     */
    public function findOne(array $condition, array $with = [])
    {
        /**
         * @var Participant $model
         */
        $model = Participant::find()->where($condition)->with($with)->one();

        if (!$model) {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param array $with
     * @return ParticipantEntity[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = Participant::find()->with('project')
                                     ->with($with)
                                     ->where($condition)
                                     ->offset($offset)
                                     ->limit($limit)
                                     ->orderBy($orderBy)
                                     ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function add(ParticipantEntity $participant)
    {
        $model = new Participant();

        $this->builderBehavior->assignProperties($model, $participant);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save participant with user_id = ' . $participant->getUserId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function update(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if (!$model) {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $participant);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update participant with id = ' . $participant->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function delete(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if (!$model) {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        if ($model->deleted) {
            throw new Exception('Participant with id = ' . $participant->getId() . ' already deleted');
        }

        $model->deleted = true;
        $model->deleted_at = time();

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete participant with id = ' . $participant->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Participant::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * Возвращает только участников проекта
     *
     * @return ParticipantEntity[]|\common\models\interfaces\IEntity[]
     */
    public function getParticipantsInProjects()
    {
        return $this->findAll([
            'user_id' => Yii::$app->user->getId(),
            'approved' => true,
            'blocked' => false,
            'deleted' => false
        ]);
    }

    /**
     * Возвращает условия для любого отношения
     * к проету(участник, на рассмотрении, забанен итд.)
     *
     * @return ParticipantEntity[]|\common\models\interfaces\IEntity[]
     */
    public function getRelationToProjects()
    {
        return $this->findAll([
            'user_id' => Yii::$app->user->getId(),
            'deleted' => false
        ]);
    }
}