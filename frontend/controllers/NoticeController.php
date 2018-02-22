<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\notice\NoticeRepository;
use frontend\models\notice\NoticeDeleteModel;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;

class NoticeController extends Controller
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

    public function actionIndex()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'recipient_id' => Yii::$app->user->getId(),
            ],
            'repositoryInstance' => NoticeRepository::instance(),
            'orderBy' => 'created_at DESC',
            'with' => ['sender']
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }


    //###################### AJAX ACTIONS ######################


    public function actionDelete()
    {
        $model = new NoticeDeleteModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }
}