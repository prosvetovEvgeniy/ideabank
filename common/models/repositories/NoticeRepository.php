<?php

namespace common\models\repositories;

use common\components\helpers\LinkHelper;
use common\components\helpers\NoticeHelper;
use common\models\activerecords\Notice;
use common\models\builders\NoticeEntityBuilder;
use common\models\entities\CommentEntity;
use common\models\entities\CommentNoticeEntity;
use common\models\entities\NoticeEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskNoticeEntity;
use common\models\interfaces\INotice;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class NoticeRepository
 * @package common\models\repositories
 *
 * @property NoticeEntityBuilder $builderBehavior
 */
class NoticeRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new NoticeEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return NoticeRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return NoticeEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Notice::findOne($condition);

        if(!$model)
        {
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
     * @return NoticeEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Notice::find()->where($condition)
                                ->with('sender')
                                ->offset($offset)
                                ->limit($limit)
                                ->orderBy($orderBy)
                                ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function add(NoticeEntity $notice)
    {
        $model = new Notice();

        $this->builderBehavior->assignProperties($model, $notice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save notice with link = ' . $notice->getLink());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function update(NoticeEntity $notice)
    {
        $model = Notice::findOne(['id' => $notice->getId()]);

        if(!$model)
        {
            throw new Exception('Notice with id = ' . $notice->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $notice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update notice with id = ' . $notice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(NoticeEntity $notice)
    {
        $model = Notice::findOne(['id' => $notice->getId()]);

        if(!$model)
        {
            throw new Exception('Notice with id = ' . $notice->getId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete notice with id = ' . $notice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Notice::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param TaskEntity $task
     * @param string $link
     * @throws Exception
     */
    public function saveNoticesForTask(TaskEntity $task, string $link)
    {
        $noticeHelper = new NoticeHelper($task->getContent());

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser)
        {
            $notice = $this->add(new NoticeEntity(
                $noticedUser->getId(),
                $task->getContent(),
                $link,
                $task->getAuthorId()
            ));

            TaskNoticeRepository::instance()->add(new TaskNoticeEntity(
                $task->getId(),
                $notice->getId()
            ));
        }
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public function saveNoticesForComment(CommentEntity $comment)
    {
        $noticeHelper = new NoticeHelper($comment->getContent());

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser)
        {
            $notice = $this->add(new NoticeEntity(
                $noticedUser->getId(),
                $comment->getContent(),
                LinkHelper::getLinkOnComment($comment, $noticedUser),
                $comment->getSenderId()
            ));

            CommentNoticeRepository::instance()->add(new CommentNoticeEntity(
                $comment->getId(),
                $notice->getId()
            ));
        }
    }

    /**
     * @param CommentEntity $comment
     * @throws Exception
     */
    public function saveNoticesForPrivateComment(CommentEntity $comment)
    {
        $noticeHelper = new NoticeHelper($comment->getContent());

        foreach ($noticeHelper->getNoticedUsers() as $noticedUser)
        {
            $isManager = Yii::$app->user->is(ParticipantEntity::ROLE_MANAGER, $comment->getTask()->getProjectId(), $noticedUser->getId());

            if($comment->getSenderId() === $noticedUser->getId() || $isManager)
            {
                $notice = $this->add(new NoticeEntity(
                    $noticedUser->getId(),
                    $comment->getContent(),
                    LinkHelper::getLinkOnComment($comment, $noticedUser),
                    $comment->getSenderId()
                ));

                CommentNoticeRepository::instance()->add(new CommentNoticeEntity(
                    $comment->getId(),
                    $notice->getId()
                ));
            }
        }
    }

    /**
     * @param INotice[] $notices
     */
    public function deleteAll(array $notices)
    {
        $noticeIds = ArrayHelper::getColumn($notices, function($notice){
           /**
            * @var INotice $notice
            */
           return $notice->getNoticeId();
        });

        Notice::deleteAll(['in', 'id', $noticeIds]);
    }
}