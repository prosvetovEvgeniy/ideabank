<?php

namespace frontend\controllers;

use frontend\models\participant\AddParticipantModel;
use frontend\models\participant\BlockParticipantModel;
use frontend\models\participant\CancelParticipantModel;
use frontend\models\participant\UnBlockParticipantModel;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class ParticipantController extends Controller
{
    public function actionAdd()
    {
        $model = new AddParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()){
            throw new BadRequestHttpException();
        }
    }

    public function actionCancel()
    {
        $model = new CancelParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()){
            throw new BadRequestHttpException();
        }
    }

    public function actionBlock()
    {
        $model = new BlockParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()){
            throw new BadRequestHttpException();
        }
    }

    public function actionUnBlock()
    {
        $model = new UnBlockParticipantModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->save()){
            throw new BadRequestHttpException();
        }
    }
}