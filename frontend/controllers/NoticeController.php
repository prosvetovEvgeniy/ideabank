<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\NoticeRepository;
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
            'orderBy' => 'createdAt DESC'
        ]);

        return $this->render('index');
    }
}