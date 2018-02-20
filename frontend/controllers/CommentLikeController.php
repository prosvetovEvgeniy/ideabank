<?php

namespace frontend\controllers;

use frontend\models\comment\CommentVoteModel;
use yii\web\Controller;
use Yii;
use yii\web\BadRequestHttpException;

class CommentLikeController extends Controller
{
    public function actionAddVote()
    {
        $model = new CommentVoteModel();
        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->add()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionDeleteVote()
    {
        $model = new CommentVoteModel();
        $model->scenario = CommentVoteModel::SCENARIO_DELETE;
        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionReverseVote()
    {
        $model = new CommentVoteModel();
        $model->userId = Yii::$app->user->getId();

        if (!$model->load(Yii::$app->request->post()) || !$model->reverse()) {
            throw new BadRequestHttpException();
        }
    }
}