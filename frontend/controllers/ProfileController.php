<?php

namespace frontend\controllers;


use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionSelfData()
    {
        return $this->render('selfdata');
    }

    public function actionSettings()
    {
        return $this->render('settings');
    }
}