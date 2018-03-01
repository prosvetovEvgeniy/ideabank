<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;

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

    public function actionError()
    {
        $className = get_class(Yii::$app->errorHandler->exception);

        if ($className === ForbiddenHttpException::class) {
            return $this->render("@frontend/views/error/forbidden");
        } elseif ($className === NotAcceptableHttpException::class) {
            return $this->render("@frontend/views/error/not-acceptable");
        } else {
            return $this->render("@frontend/views/error/error");
        }
    }
}