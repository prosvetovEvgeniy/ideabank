<?php

namespace common\models\repositories;


use common\models\builders\CommentViewEntityBuilder;
use common\models\interfaces\IRepository;
use Yii;
use common\models\activerecords\CommentView;
use common\models\entities\CommentEntity;
use common\models\activerecords\Comment;
use yii\base\NotSupportedException;

/**
 * Этот репозиторий используется
 * только для отображения комментириев
 * с помощью жадной загрузки
 *
 * Class CommentViewRepository
 * @package common\models\repositories
 *
 * @property CommentViewEntityBuilder $builderBehavior
 */
class CommentViewRepository implements IRepository
{
    public const COMMENTS_PER_PAGE = 30;

    private $builderBehavior;


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

    public function findOne(array $condition)
    {
        throw new NotSupportedException();
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
        $identity = Yii::$app->user->identity;

        /*
         * если пользователь не зарегистрирован, то он и не оставлял
         * комментарий => поле current_user_liked_it будет FALSE
         * (данное поле используется на frontend). Аналогично
         * для поля current_user_disliked_it
         */
        $userId = ($identity !== null) ? $identity->getId() : 'NULL';

        $models = CommentView::find()->where($condition)
                                     ->with('user')
                                     ->with('parent')
                                     ->joinWith('commentLikes')
                                     ->addSelect('comment.*')
                                     ->addSelect('SUM(CASE WHEN comment_like.liked = TRUE THEN 1 ELSE 0 END) AS likes_amount')
                                     ->addSelect('SUM(CASE WHEN comment_like.liked = FALSE THEN 1 ELSE 0 END) AS dislikes_amount')
                                     ->addSelect('(CASE WHEN comment_like.user_id = ' . $userId . ' AND comment_like.liked = TRUE THEN TRUE ELSE FALSE END) as current_user_liked_it')
                                     ->addSelect('(CASE WHEN comment_like.user_id = ' . $userId . ' AND comment_like.liked = FALSE THEN TRUE ELSE FALSE END) as current_user_disliked_it')
                                     ->groupBy('comment.id, comment_like.user_id, comment_like.liked')
                                     ->orderBy('id ASC')
                                     ->limit($limit)
                                     ->offset($offset)
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
        return (int) Comment::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * Рассчитывает количество комментриев до текущего
     *
     * @param CommentEntity $comment
     * @return int|string
     */
    public function getCountRecordsBeforeComment(CommentEntity $comment)
    {
        return $this->getTotalCountByCondition([
            'and',
            ['task_id' => $comment->getTaskId()],
            ['<', 'id', $comment->getId()],
            ['<', 'created_at', $comment->getCreatedAt()]
        ]);
    }
}