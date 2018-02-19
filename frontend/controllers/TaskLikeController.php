<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use Yii;
use frontend\models\task\TaskVoteModel;

class TaskLikeController extends Controller
{
    public function actionAddVote()
    {
        $model = new TaskVoteModel();
        $model->userId = Yii::$app->user->identity->getUserId();

        if (!$model->load(Yii::$app->request->post()) || !$model->add()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionDeleteVote()
    {
        $model = new TaskVoteModel();
        $model->scenario = TaskVoteModel::SCENARIO_DELETE;

        $model->userId = Yii::$app->user->identity->getUserId();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionReverseVote()
    {
        $model = new TaskVoteModel();
        $model->userId = Yii::$app->user->identity->getUserId();

        if (!$model->load(Yii::$app->request->post()) || !$model->reverse()) {
            throw new BadRequestHttpException();
        }
    }
}