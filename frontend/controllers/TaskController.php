<?php

namespace frontend\controllers;


use common\models\searchmodels\TaskEntitySearch;
use yii\web\Controller;
use Yii;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TaskEntitySearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel
        ]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}