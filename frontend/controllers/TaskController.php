<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\CommentRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\TaskRepository;
use common\models\searchmodels\TaskEntitySearch;
use yii\db\Exception;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TaskEntitySearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 10);

        $participants = ParticipantRepository::instance()->getParticipantsInProjects(Yii::$app->user->identity->getEntity());

        return $this->render('index', [
            'dataProvider'   => $dataProvider,
            'searchModel'    => $searchModel,
            'currentProject' => $searchModel->getProject(),
            'participants'   => $participants
        ]);
    }

    public function actionView()
    {
        try
        {
            $task = TaskRepository::instance()->findOne(['id' => Yii::$app->request->get('taskId')]);
        }
        catch (Exception $e) {
            throw new NotFoundHttpException();
        }

        $dataProvider = new EntityDataProvider([
            'condition' => [
                'task_id' => $task->getId()
            ],
            'repositoryInstance' => CommentRepository::instance(),
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        return $this->render('view',[
            'task'         => $task,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}