<?php

namespace common\components\helpers;


use common\models\entities\CommentEntity;
use common\models\entities\TaskEntity;
use common\models\repositories\CommentRepository;
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
     * @return string
     */
    public static function getLinkOnComment(CommentEntity $comment)
    {
        //расчитываем номер страницы, на которой будет находится комментарий
        $count = CommentRepository::instance()->getCountRecordsBeforeComment($comment);
        $perPage = CommentViewRepository::COMMENTS_PER_PAGE;

        $pageNumber = floor($count/$perPage) + 1;

        //из класс yii\data\Pagination получаем названия GET-параметров (page, per-page)
        $pagination = new Pagination();

        return Yii::$app->urlManager->createAbsoluteUrl([
            Yii::$app->request->pathInfo,
            'id' => $comment->getTaskId(),
            $pagination->pageParam => $pageNumber,
            $pagination->pageSizeParam => CommentViewRepository::COMMENTS_PER_PAGE,
            '#' => $comment->getId()
        ]);
    }
}