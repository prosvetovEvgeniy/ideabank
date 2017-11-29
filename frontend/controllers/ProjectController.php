<?php

namespace frontend\controllers;

use common\models\repositories\ParticipantRepository;
use yii\web\Controller;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects();

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