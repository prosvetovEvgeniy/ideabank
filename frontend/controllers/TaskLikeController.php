<?php

namespace frontend\controllers;

use common\models\entities\TaskLikeEntity;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use Yii;
use common\models\repositories\TaskLikeRepository;
use yii\db\Exception;
use frontend\models\task\TaskVoteModel;

class TaskLikeController extends Controller
{
    /**
     * @throws BadRequestHttpException
     */
    public function actionAddvote()
    {
        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $model = new TaskVoteModel();
        $model->userId = Yii::$app->user->identity->getEntity()->getId();

        $model->load(Yii::$app->request->post());

        if(!$model->validate() || $model->recordExist())
        {
            throw new BadRequestHttpException();
        }

        $taskLike = new TaskLikeEntity($model->taskId, $model->userId, $model->liked);

        try
        {
            TaskLikeRepository::instance()->add($taskLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionDeletevote()
    {
        $taskId = Yii::$app->request->post('taskId');

        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $taskId,
            'user_id' => $userId,
        ]);

        if(!$taskLike)
        {
            throw new BadRequestHttpException();
        }

        try
        {
            TaskLikeRepository::instance()->delete($taskLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionReversevote()
    {
        $taskId = Yii::$app->request->post('taskId');

        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $taskId,
            'user_id' => $userId,
        ]);

        if(!$taskLike)
        {
            throw new BadRequestHttpException();
        }

        ($taskLike->getLiked() === true) ? $taskLike->dislike() : $taskLike->like();

        try
        {
            TaskLikeRepository::instance()->update($taskLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }
}