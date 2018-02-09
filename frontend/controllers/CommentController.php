<?php

namespace frontend\controllers;


use frontend\models\comment\CommentDeleteModel;
use frontend\models\comment\CommentEditModel;
use frontend\models\comment\CommentPrivateModel;
use frontend\models\comment\CommentPublicModel;
use frontend\models\comment\CommentReestablishModel;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class CommentController extends Controller
{
    public function actionEdit()
    {
        $model = new CommentEditModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->update())
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionDelete()
    {
        $model = new CommentDeleteModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->delete())
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionReestablish()
    {
        $model = new CommentReestablishModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->update())
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionMakePrivate()
    {
        $model = new CommentPrivateModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->update())
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionMakePublic()
    {
        $model = new CommentPublicModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->update())
        {
            throw new BadRequestHttpException();
        }
    }
}