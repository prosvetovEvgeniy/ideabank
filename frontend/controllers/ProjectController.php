<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use common\models\searchmodels\participant\ParticipantSearchForm;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;

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
        $participants = ParticipantRepository::instance()->getParticipantsInProjects();

        return $this->render('index', ['participants' => $participants]);
    }

    public function actionView(int $id)
    {
        $project = ProjectRepository::instance()->findOne(['id' => $id]);

        if (!$project) {
            throw new BadRequestHttpException();
        }

        if (Yii::$app->user->participantHadBlockedRole($project->getId())) {
            throw new ForbiddenHttpException();
        }

        if (Yii::$app->user->isBlocked($id)) {
            throw new ForbiddenHttpException();
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
            ],
            'with' => ['company']
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

        if (!Yii::$app->user->isManager($searchModel->projectId)) {
            throw new NotAcceptableHttpException();
        }

        return $this->render('participants', [
            'model'        => $searchModel,
            'dataProvider' => $searchModel->search()
        ]);
    }

    public function actionParticipantView(int $id)
    {
        $participant = ParticipantRepository::instance()->findOne(['id' => $id]);

        if (!$participant) {
            throw new BadRequestHttpException();
        }

        if (!Yii::$app->user->isManager($participant->getProjectId())) {
            throw new NotAcceptableHttpException();
        }

        return $this->render('participant-view', [
            'participant' => $participant
        ]);
    }
}








