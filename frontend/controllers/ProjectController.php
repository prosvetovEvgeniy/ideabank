<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use frontend\models\project\JoinToProjectModel;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use common\models\searchmodels\project\ParticipantSearchForm;
use yii\web\Controller;
use Yii;

class ProjectController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'participants'],
                'rules' => [
                    [
                        'actions' => ['index', 'participants'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $participants = ParticipantRepository::instance()->getRelationToProjects();

        return $this->render('index', ['participants' => $participants]);
    }

    public function actionView(int $id)
    {
        $project = ProjectRepository::instance()->findOne(['id' => $id]);

        if (!$project) {
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
                ['like', 'lower(name)', mb_strtolower($projectName)]
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

    public function actionParticipants()
    {
        $searchModel = new ParticipantSearchForm();

        if (!$searchModel->load(Yii::$app->request->queryParams) || !$searchModel->validate()) {
            throw new BadRequestHttpException();
        }

        return $this->render('participants', [
            'model'        => $searchModel,
            'dataProvider' => $searchModel->search()
        ]);
    }
}








