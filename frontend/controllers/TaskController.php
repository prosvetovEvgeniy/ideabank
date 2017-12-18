<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\CommentRepository;
use common\models\repositories\CommentViewRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\TaskRepository;
use common\models\searchmodels\TaskEntitySearch;
use frontend\models\CommentForm;
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

    public function actionView($taskId)
    {
        $task = TaskRepository::instance()->findOne(['id' => $taskId]);

        if(!$task)
        {
            throw new NotFoundHttpException();
        }

        $dataProvider = new EntityDataProvider([
            'condition' => [
                'task_id' => $task->getId()
            ],
            'repositoryInstance' => CommentViewRepository::instance(),
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        $model = new CommentForm();
        $model->taskId = $task->getId();

        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if($model->saveComment())
            {
                $model = new CommentForm();
            }
        }

        return $this->render('view',[
            'task'         => $task,
            'dataProvider' => $dataProvider,
            'model'        => $model
        ]);
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}