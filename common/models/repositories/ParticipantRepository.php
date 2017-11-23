<?php

namespace common\models\repositories;


use common\models\activerecords\Participant;
use common\models\entities\ParticipantEntity;
use yii\db\Exception;
use Yii;

class ParticipantRepository
{
    /**
     * Возвращает экземпляр класса
     *
     * @return ParticipantRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return ParticipantEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Participant::findOne($condition);

        if(!$model)
        {
            throw new Exception('Participant with ' . json_encode($condition) . ' does not exists');
        }

        if($model->blocked)
        {
            throw new Exception('Participant with ' . json_encode($condition) . ' already blocked');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return ParticipantEntity[]
     * @throws Exception
     */
    public function findAll(array $condition)
    {
        /** @var Participant[] $models */
        $models = Participant::findAll($condition);

        if(!$models)
        {
            return [];
        }

        $entities = [];

        foreach ($models as $model)
        {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }

    /**
     * Добавляет сущность в БД
     *
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function add(ParticipantEntity $participant)
    {
        $model = new Participant();

        $this->assignProperties($model, $participant);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save participant with user_id = ' . $participant->getUserId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function update(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if(!$model)
        {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        $this->assignProperties($model, $participant);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update participant with id = ' . $participant->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function block(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if(!$model)
        {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        if($model->blocked)
        {
            throw new Exception('Participant with id = ' . $participant->getId() . ' already blocked');
        }

        $model->blocked = true;
        $model->blocked_at = time();

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete participant with id = ' . $participant->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Participant $model
     * @param ParticipantEntity $participant
     */
    protected function assignProperties(&$model, &$participant)
    {
        $model->user_id = $participant->getUserId();
        $model->company_id = $participant->getCompanyId();
        $model->project_id = $participant->getProjectId();
        $model->approved = $participant->getApproved();
        $model->approved_at = $participant->getApprovedAt();
        $model->blocked = $participant->getBlocked();
        $model->blocked_at = $participant->getBlockedAt();
    }

    /**
     * @param Participant $model
     * @return ParticipantEntity
     */
    protected function buildEntity(Participant $model)
    {
        return new ParticipantEntity($model->user_id, $model->id, $model->company_id, $model->project_id,
                                     $model->approved, $model->approved_at, $model->blocked, $model->blocked_at,
                                     $model->created_at, $model->updated_at);
    }
}