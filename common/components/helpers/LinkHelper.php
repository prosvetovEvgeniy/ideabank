<?php

namespace common\components\helpers;


use common\models\entities\CommentEntity;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
use common\models\repositories\CommentViewRepository;
use common\models\searchmodels\task\TaskEntitySearch;
use Yii;


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
        $index = CommentHelper::getNewCommentIndex($comment, $user);
        $perPage = CommentViewRepository::COMMENTS_PER_PAGE;

        if($index % $perPage === 0) {
            $pageNumber = $index/$perPage;
        }
        else {
            $pageNumber = floor($index/$perPage) + 1;
        }

        return Yii::$app->urlManager->createAbsoluteUrl([
            'task/view',
            'id'       => $comment->getTaskId(),
            'page'     => $pageNumber,
            'per-page' => $perPage,
            '#'        => $comment->getId()
        ]);
    }

    /**
     * @param ProjectEntity $project
     * @return string
     */
    public static function getLinkOnTaskIndex(ProjectEntity $project)
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'task/index',
            'TaskEntitySearch[projectId]' => $project->getId(),
            'TaskEntitySearch[status]'    => TaskEntitySearch::STATUS_ALL,
        ]);
    }
}