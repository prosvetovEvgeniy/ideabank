<?php

namespace common\models\repositories\comment;

use common\models\builders\CommentViewEntityBuilder;
use common\models\entities\AuthAssignmentEntity;
use common\models\interfaces\IRepository;
use common\models\repositories\task\TaskRepository;
use Yii;
use common\models\activerecords\CommentView;
use common\models\entities\CommentEntity;

/**
 * Этот репозиторий используется
 * только для отображения комментириев
 * с помощью жадной загрузки
 *
 * Class CommentViewRepository
 * @package common\models\repositories\comment
 *
 * @property CommentViewEntityBuilder $builderBehavior
 */
class CommentViewRepository implements IRepository
{
    public const COMMENTS_PER_PAGE = 40;

    private $builderBehavior;

    /**
     * CommentViewRepository constructor.
     */
    public function __construct()
    {
        $this->builderBehavior = new CommentViewEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return CommentViewRepository
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
     * @return CommentEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        /*
         * если пользователь не зарегистрирован, то он и не оставлял
         * комментарий => поле current_user_liked_it будет FALSE
         * (данное поле используется на frontend). Аналогично
         * для поля current_user_disliked_it
         */
        $userId = Yii::$app->user->identity->getUserId() ?? 'NULL';

        $models = CommentView::find()->addSelect('c.*')
                                     ->addSelect('(SELECT COUNT(*) FROM comment_like WHERE comment_id = c.id AND liked = TRUE) as likes_amount')
                                     ->addSelect('(SELECT COUNT(*) FROM comment_like WHERE comment_id = c.id AND liked = FALSE) as dislikes_amount')
                                     ->addSelect('EXISTS(SELECT id FROM comment_like WHERE comment_id = c.id AND liked = TRUE AND user_id = ' . $userId . ') as current_user_liked_it')
                                     ->addSelect('EXISTS(SELECT id FROM comment_like WHERE comment_id = c.id AND liked = FALSE AND user_id = ' . $userId . ') as current_user_disliked_it')
                                     ->from('comment c')
                                     ->where($condition)
                                     ->with('user')
                                     ->with('parent');

        $task = TaskRepository::instance()->findOne(['id' => $condition['task_id']]);

        if (!Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $task->getProjectId())) {
            $models = $models->andWhere([
                'or',
                ['private' => false],
                ['sender_id' => $userId]
            ]);
        }

        $models = $models->orderBy($orderBy)->limit($limit)->offset($offset)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        $userId = Yii::$app->user->identity->getUserId() ?? 'NULL';

        $models= CommentView::find()->where($condition);

        $task = TaskRepository::instance()->findOne(['id' => $condition['task_id']]);

        if (!Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $task->getProjectId())) {
            $models = $models->andWhere([
                'or',
                ['private' => false],
                ['sender_id' => $userId]
            ]);
        }

        return (int) $models->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################

}