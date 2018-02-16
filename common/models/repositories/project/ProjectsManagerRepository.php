<?php

namespace common\models\repositories\project;


use common\models\activerecords\Project;
use common\models\builders\ProjectEntityBuilder;
use common\models\entities\ParticipantEntity;
use common\models\interfaces\IRepository;
use Yii;

/**
 * Возвращает проекты в которых текущий пользователь менеджер
 *
 * Class ProjectManagerRepository
 * @package common\models\repositories
 *
 * @property ProjectEntityBuilder $builderBehavior
 */
class ProjectsManagerRepository implements IRepository
{
    private $builderBehavior;

    /**
     * ProjectManagerRepository constructor.
     */
    public function __construct()
    {
        $this->builderBehavior = new ProjectEntityBuilder();
    }

    /**
     * @return IRepository
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
     * @return \common\models\entities\ProjectEntity[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Project::find()->from('project p')
                                 ->leftJoin('participant pc', 'pc.project_id = p.id')
                                 ->leftJoin('auth_assignment a_u', 'a_u.user_id = pc.id')
                                 ->where([
                                     'and',
                                     ['pc.user_id'    => Yii::$app->user->identity->getUserId()],
                                     ['not', ['a_u.item_name' => ParticipantEntity::ROLE_USER]]
                                 ])
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy('p.id ASC')
                                 ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Project::find()->from('project p')
                                    ->leftJoin('participant pc ON pc.project_id = p.id')
                                    ->leftJoin('auth_assignment a_u ON a_u.user_id = pc.id')
                                    ->where([
                                        'p.user_id' => Yii::$app->user->identity->getUserId(),
                                        'a_u.item_name' => ParticipantEntity::ROLE_MANAGER
                                     ])
                                     ->count();
    }
}