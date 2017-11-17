<?php

namespace frontend\controllers;


use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $projectName = Yii::$app->request->get('projectName');

        return $this->render('index', ['projectName' => $projectName]);
    }

    public function actionSearch()
    {
        return $this->render('search');
    }
}