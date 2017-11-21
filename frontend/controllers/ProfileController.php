<?php

namespace frontend\controllers;


use common\models\activerecords\Task;
use yii\debug\models\timeline\DataProvider;
use yii\web\Controller;
use Yii;
use yii\data\ActiveDataProvider;

class ProfileController extends Controller
{
    public function actionIndex()
    {

        $query = Task::find()->where(['author_id' => Yii::$app->user->identity->profile->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],

        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}