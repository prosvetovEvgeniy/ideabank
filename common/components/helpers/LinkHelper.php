<?php

namespace common\components\helpers;


use common\models\entities\CommentEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
use common\models\repositories\CommentViewRepository;
use Yii;
use yii\data\Pagination;

class LinkHelper
{
    /**
     * @param TaskEntity $task
     * @return string
     */
    public static function getLinkOnTask(TaskEntity $task)
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            '/task/view',
            'id' => $task->getId()
        ]);
    }

    /**
     * @param CommentEntity $comment
     * @param UserEntity $user
     * @return string
     */
    public static function getLinkOnComment(CommentEntity $comment, UserEntity $user = null)
    {
        $user = $user ?? Yii::$app->user->identity->getUser();

        //расчитываем номер страницы, на которой будет находится комментарий
        $index = CommentViewRepository::instance()->getNewCommentIndex($comment, $user);
        $perPage = CommentViewRepository::COMMENTS_PER_PAGE;

        if($index % $perPage === 0)
        {
            $pageNumber = $index/$perPage;
        }
        else
        {
            $pageNumber = floor($index/$perPage) + 1;
        }

        //из класс yii\data\Pagination получаем названия GET-параметров (page, per-page)
        $pagination = new Pagination();

        return Yii::$app->urlManager->createAbsoluteUrl([
            'task/view',
            'id' => $comment->getTaskId(),
            $pagination->pageParam => $pageNumber,
            $pagination->pageSizeParam => CommentViewRepository::COMMENTS_PER_PAGE,
            '#' => $comment->getId()
        ]);
    }
}