<?php

namespace frontend\controllers;


use common\components\helpers\ParticipantHelper;
use common\models\activerecords\Participant;
use common\models\activerecords\Project;
use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $participants = Participant::find()->where(['user_id' => Yii::$app->user->identity->profile->id])
                                            ->andWhere(['is not', 'company_id', null])
                                            ->andWhere(['is not', 'project_id', null])
                                            ->all();


        return $this->render('index', ['participants' => $participants]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionSearch()
    {
        return $this->render('search');
    }
}