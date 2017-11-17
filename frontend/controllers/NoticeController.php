<?php

namespace frontend\controllers;

use yii\web\Controller;

class NoticeController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}