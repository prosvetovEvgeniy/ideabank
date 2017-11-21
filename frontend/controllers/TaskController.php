<?php

namespace frontend\controllers;


use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }
}