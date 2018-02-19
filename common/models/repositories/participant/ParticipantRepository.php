<?php

namespace common\models\repositories\participant;

use common\models\activerecords\Participant;
use common\models\builders\ParticipantEntityBuilder;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use common\models\interfaces\IRepository;
use common\models\repositories\user\UserRepository;
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
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return ParticipantEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Participant::findOne($condition);

        if (!$model) {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
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

        return $this->builderBehavior->buildEntities($models);
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

        $this->builderBehavior->assignProperties($model, $participant);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save participant with user_id = ' . $participant->getUserId());
        }

        return $this->builderBehavior->buildEntity($model);
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
     * Помечает сущность как удаленную в БД
     *
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function block(ParticipantEntity $participant)
    {
        $model = Participant::findOne(['id' => $participant->getId()]);

        if (!$model) {
            throw new Exception('Participant with id = ' . $participant->getId() . ' does not exists');
        }

        if ($model->blocked) {
            throw new Exception('Participant with id = ' . $participant->getId() . ' already blocked');
        }

        $model->blocked = true;
        $model->blocked_at = time();

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot block participant with id = ' . $participant->getId());
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
     * Возвращает проекты, в которым присоединен пользователь
     *
     * @return ParticipantEntity[]
     */
    public function getParticipantsInProjects()
    {
        $condition = $this->getConditionOnParticipantsInProjects();

        return $this->findAll($condition);
    }

    /**
     * Возвращает пользователя с любым отношением
     * к проету(участник, на рассмотрении)
     *
     * @return ParticipantEntity[]
     */
    public function getRelationToProjects()
    {
        $condition = $this->getConditionOnRelationToProject();

        return $this->findAll($condition);
    }

    /**
     * Позвращает условия только для участников проектов
     *
     * @return array
     */
    public function getConditionOnParticipantsInProjects()
    {
        $user = Yii::$app->user->identity->getUser();

        return [
            'and',
            ['user_id' => $user->getId()],
            ['not', ['company_id' => null]],
            ['not', ['project_id' => null]],
            ['approved' => true],
            ['blocked' => false],
            ['deleted' => false]
        ];
    }

    /**
     * Возвращает условия для любого отношения
     * к проету(участник, на рассмотрении)
     *
     * @return array
     */
    public function getConditionOnRelationToProject()
    {
        $user = Yii::$app->user->identity->getUser();

        return [
            'and',
            ['user_id' => $user->getId()],
            ['not', ['company_id' => null]],
            ['not', ['project_id' => null]],
            ['blocked' => false],
            ['deleted' => false]
        ];
    }

    /**
     * @param UserEntity|null $user
     * @return ParticipantEntity[]
     */
    public function getDeletedParticipants(UserEntity $user = null)
    {
        $user = Yii::$app->user->identity->getUser();

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

        if (!$user) {
            return null;
        }

        return $this->findOne([
            'user_id'    => $user->getId(),
            'company_id' => null
        ]);
    }

    /**
     * @return ParticipantEntity
     */
    public function getParticipantStub()
    {
        $userId = Yii::$app->user->identity->getUserId();

        return $this->findOne([
            'user_id'    => $userId,
            'project_id' => null,
            'company_id' => null
        ]);
    }
}