<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\ProjectRepository;
use frontend\models\project\JoinToProjectModel;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects(Yii::$app->user->identity->getUser());

        return $this->render('index', ['participants' => $participants]);
    }

    public function actionView(int $id)
    {
        try
        {
            $project = ProjectRepository::instance()->findOne(['id' => $id]);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }

        return $this->render('view', ['project' => $project]);
    }

    public function actionSearch(string $projectName)
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'and',
                ['deleted' => false],
                ['like', 'lower(name)', strtolower($projectName)]
            ],
            'repositoryInstance' => ProjectRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'projectName'  => $projectName
        ]);
    }


    //############### AJAX ACTIONS ##################


    public function actionJoin()
    {
        $model = new JoinToProjectModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->save())
        {
            throw new BadRequestHttpException();
        }
    }
}








