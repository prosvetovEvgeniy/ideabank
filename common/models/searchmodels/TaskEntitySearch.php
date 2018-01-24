<?php

namespace common\models\searchmodels;


use common\components\dataproviders\EntityDataProvider;
use common\models\interfaces\ISearchEntityModel;
use common\models\repositories\ProjectRepository;
use common\models\repositories\TaskRepository;
use yii\base\Model;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
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
 */
class TaskEntitySearch extends Model implements ISearchEntityModel
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

    /**
     * массив содержит в себе
     * имена переменных, которые
     * нужно пропустить при построении
     * like-запроса
     */
    protected $skipOnBuildLike = [
        'projectId',
        'status'
    ];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'string'],
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
     * @throws NotFoundHttpException
     */
    public function search(int $pageSize = 20): EntityDataProvider
    {
        $condition = $this->buildCondition();

        $dataProvider = new EntityDataProvider([
            'condition' => $condition,
            'repositoryInstance' => TaskRepository::instance(),
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Осуществляет построение условия для поиска
     * на основании полученных атрибутов модели
     *
     * @return array
     * @throws NotFoundHttpException
     */
    protected function buildCondition()
    {
        $condition[] = 'and';

        foreach ($this->attributes as $key => $value)
        {
            if($value === null)
            {
                continue;
            }

            if(!in_array($key, $this->skipOnBuildLike))
            {
                $condition[] = ['like', "lower({$key})", strtolower($value)];
            }
            elseif ($key === 'status')
            {
                $project = $this->getProject();

                if($value === self::STATUS_ALL)
                {
                    $condition[] = TaskRepository::instance()->getConditionOnAllTasks($project);
                }
                elseif ($value === self::STATUS_COMPLETED)
                {
                    $condition[] = TaskRepository::instance()->getConditionOnCompletedTasks($project);
                }
                elseif ($value === self::STATUS_NOT_COMPLETED)
                {
                    $condition[] = TaskRepository::instance()->getConditionOnNotCompletedTasks($project);
                }
                elseif ($value === self::STATUS_MERGED)
                {
                    $condition[] = TaskRepository::instance()->getConditionOnMergedTasks($project);
                }
                elseif ($value === self::STATUS_OWN)
                {
                    $condition[] = TaskRepository::instance()->getConditionByAuthorForProject($project, Yii::$app->user->identity->getUser());
                }
            }
        }

        return $condition;
    }

    /**
     * @return \common\models\entities\ProjectEntity
     * @throws NotFoundHttpException
     */
    public function getProject()
    {
        try
        {
            return ProjectRepository::instance()->findOne(['id' => $this->projectId]);
        }
        catch (Exception $e)
        {
            throw new NotFoundHttpException();
        }
    }
}