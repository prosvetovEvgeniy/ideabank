<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\NoticeRepository;
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
                'recipient_id' => Yii::$app->user->identity->getUser()->getId(),
                'viewed' => false
            ],
            'repositoryInstance' => NoticeRepository::instance(),
            'orderBy' => 'created_at DESC, viewed'
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDelete()
    {
        $model = new NoticeDeleteModel();
        $model->recipientId = Yii::$app->user->identity->getUser()->getId();
        $model->id = Yii::$app->request->get('id');

        if(!$model->delete())
        {
            throw new BadRequestHttpException();
        }

        return $this->redirect('index');
    }
}