<?php

namespace frontend\controllers;

use frontend\models\participant\AddParticipantModel;
use frontend\models\participant\BlockParticipantModel;
use frontend\models\participant\CancelParticipantModel;
use frontend\models\participant\JoinParticipantModel;
use frontend\models\participant\UnBlockParticipantModel;
use frontend\models\participant\DeleteParticipantModel;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class ParticipantController extends Controller
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

    public function actionAdd()
    {
        $model = new AddParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionCancel()
    {
        $model = new CancelParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionBlock()
    {
        $model = new BlockParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionUnBlock()
    {
        $model = new UnBlockParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionDelete()
    {
        $model = new DeleteParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionJoin()
    {
        $model = new JoinParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new BadRequestHttpException();
        }
    }
}