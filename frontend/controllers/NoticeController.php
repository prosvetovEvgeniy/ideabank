<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\notice\NoticeRepository;
use frontend\models\notice\NoticeDeleteModel;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class NoticeController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'recipient_id' => Yii::$app->user->getId(),
            ],
            'repositoryInstance' => NoticeRepository::instance(),
            'orderBy' => 'created_at DESC'
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