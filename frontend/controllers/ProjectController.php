<?php

namespace frontend\controllers;

use common\models\activerecords\Participant;
use common\models\repositories\ParticipantRepository;
use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects(Yii::$app->user->identity->getEntity());

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