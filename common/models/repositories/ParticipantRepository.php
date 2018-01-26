<?php

namespace common\models\repositories;


use common\models\activerecords\Participant;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

class ParticipantRepository implements IRepository
{

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
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return ParticipantEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Participant::findOne($condition);

        if(!$model || $model->blocked)
        {
            return null;
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return ParticipantEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Participant::find()->with('project')
                                     ->where($condition)
                                     ->offset($offset)
                                     ->limit($limit)
                                     ->orderBy($orderBy)
                                     ->all();

        return $this->buildEntities($models);
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
            throw new Exception('Cannot block participant with id = ' . $participant->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function delete(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if(!$model)
        {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Participant with id = ' . $participant->getId() . ' already deleted');
        }

        $model->deleted = true;
        $model->deleted_at = time();

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
        $model->deleted_at = $participant->getDeletedAt();
        $model->deleted = $participant->getDeleted();
    }

    /**
     * @param Participant $model
     * @return ParticipantEntity
     */
    protected function buildEntity(Participant $model)
    {
        $project = ($model->project) ? ProjectRepository::instance()->buildEntity($model->project) : null ;

        return new ParticipantEntity($model->user_id, $model->company_id, $model->project_id,
                                     $model->approved, $model->approved_at, $model->blocked, $model->blocked_at,
                                     $model->id, $model->created_at, $model->updated_at, $model->deleted_at,
                                     $model->deleted, $project);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Participant[] $models
     * @return ParticipantEntity[]
     */
    protected function buildEntities(array $models)
    {
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
     * @param $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Participant::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * Возвращает проекты, в которым присоединен пользователь
     *
     * @param UserEntity $user
     * @return ParticipantEntity[]
     */
    public function getParticipantsInProjects(UserEntity $user = null)
    {
        $condition = $this->getConditionOnParticipantsInProjects($user);

        return $this->findAll($condition);
    }

    /**
     * @param UserEntity|null $user
     * @return array
     */
    public function getConditionOnParticipantsInProjects(UserEntity $user = null)
    {
        if(!$user)
        {
            $user = Yii::$app->user->identity->getUser();
        }

        return [
            'and',
            ['user_id' => $user->getId()],
            ['not', ['company_id' => null]],
            ['not', ['project_id' => null]],
            ['deleted' => false]
        ];
    }

    /**
     * @param UserEntity|null $user
     * @return ParticipantEntity[]
     */
    public function getDeletedParticipants(UserEntity $user = null)
    {
        if(!$user)
        {
            $user = Yii::$app->user->identity->getUser();
        }

        return $this->findAll([
            'and',
            ['user_id' => $user->getId()],
            ['not', ['company_id'=> null]],
            ['not', ['project_id'=> null]],
            ['deleted' => true]
        ]);
    }

    /**
     * @param string $username
     * @return ParticipantEntity|null
     */
    public function findByUserName(string $username)
    {
        $user = UserRepository::instance()->findOne(['username' => $username]);

        if(!$user)
        {
            return null;
        }

        return $this->findOne([
            'user_id'    => $user->getId(),
            'company_id' => null
        ]);
    }
}