<?php

namespace frontend\controllers;

use common\models\repositories\ParticipantRepository;
use common\models\repositories\ProjectRepository;
use yii\db\Exception;
use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects(Yii::$app->user->identity->getUser());

        return $this->render('index', ['participants' => $participants]);
    }

    public function actionView()
    {
        $projectName = Yii::$app->request->get('projectName');

        try
        {
            $project = ProjectRepository::instance()->findOne(['name' => $projectName]);
        }
        catch (Exception $e)
        {
            return $this->render('/site/error');
        }

        return $this->render('view', ['project' => $project]);
    }

    public function actionSearch()
    {
        return $this->render('search');
    }
}