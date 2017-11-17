<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;

class CompanyController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        $companyName = Yii::$app->request->get('companyName');

        return $this->render('view', ['companyName' => $companyName]);
    }
}