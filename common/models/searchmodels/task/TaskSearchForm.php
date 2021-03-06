<?php

namespace common\models\searchmodels\task;

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\AuthAssignmentEntity;
use common\models\interfaces\IEntity;
use common\models\interfaces\ISearchEntityModel;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\task\TaskRepository;
use common\models\searchmodels\task\searchstrategy\ITaskSearchStrategy;
use common\models\searchmodels\task\searchstrategy\ManagerTaskSearchStrategy;
use common\models\searchmodels\task\searchstrategy\UnregisteredUserTaskSearchStrategy;
use common\models\searchmodels\task\searchstrategy\UserTaskSearchStrategy;
use yii\base\Model;
use common\models\entities\ProjectEntity;
use Yii;

/**
 * Class TaskEntitySearch
 * @package common\models\searchmodels
 *
 * @property string $title
 * @property string $content
 * @property string $status
 * @property int    $projectId
 *
 * @property array  $skipOnBuildLike
 * @property ITaskSearchStrategy $searchStrategyBehavior
 */
class TaskSearchForm extends Model implements ISearchEntityModel
{
    /**
     * Список статусов для поиска.
     * Статусы отличаются от
     * статусов представленных
     * в TaskEntity
     */
    public const STATUS_ALL = 'all';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_NOT_COMPLETED = 'notCompleted';
    public const STATUS_MERGED = 'merged';
    public const STATUS_OWN = 'own';

    public const LIST_STATUSES_AS_TEXT = [
        self::STATUS_ALL           => 'все',
        self::STATUS_COMPLETED     => 'завершенные',
        self::STATUS_NOT_COMPLETED => 'не завершенные',
        self::STATUS_MERGED        => 'объеденные',
        self::STATUS_OWN           => 'собственные'
    ];

    public $title;
    public $content;
    public $status;
    public $projectId;

    private $searchStrategyBehavior;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'string'],
            [['title'], 'filter', 'filter' => function($value){
                return $value ?? '';
            }],
            [['content'], 'filter', 'filter' => function($value){
                return $value ?? '';
            }],
            [['projectId'], 'integer'],
            [['status'], 'in', 'range' => [
                self::STATUS_ALL,
                self::STATUS_COMPLETED,
                self::STATUS_NOT_COMPLETED,
                self::STATUS_MERGED,
                self::STATUS_OWN
            ]]
        ];
    }

    public function afterValidate()
    {
        parent::afterValidate();

        if (Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $this->projectId)) {
            $this->searchStrategyBehavior = new ManagerTaskSearchStrategy();
        } elseif (Yii::$app->user->is(AuthAssignmentEntity::ROLE_USER, $this->projectId)) {
            $this->searchStrategyBehavior = new UserTaskSearchStrategy();
        } else {
            $this->searchStrategyBehavior = new UnregisteredUserTaskSearchStrategy();
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'   => 'Заголовок',
            'content' => 'Содержание',
            'status'  => 'Статус'
        ];
    }

    /**
     * @param int $pageSize
     * @return EntityDataProvider
     */
    public function search(int $pageSize = 20): EntityDataProvider
    {
        return new EntityDataProvider([
            'condition' =>  $this->buildCondition(),
            'repositoryInstance' => TaskRepository::instance(),
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
    }

    /**
     * @return array
     */
    private function buildCondition()
    {
        return $this->searchStrategyBehavior->buildCondition(
            $this->status,
            $this->projectId,
            $this->title,
            $this->content
        );
    }

    /**
     * @return ProjectEntity|IEntity|null
     */
    public function getProject()
    {
        return ProjectRepository::instance()->findOne(['id' => $this->projectId]);
    }
}