<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use Yii;
use frontend\models\task\TaskVoteModel;

class TaskLikeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    public function actionAddVote()
    {
        $model = new TaskVoteModel();
        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->add()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionDeleteVote()
    {
        $model = new TaskVoteModel();
        $model->scenario = TaskVoteModel::SCENARIO_DELETE;

        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionReverseVote()
    {
        $model = new TaskVoteModel();
        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->reverse()) {
            throw new BadRequestHttpException();
        }
    }
}