<?php

namespace frontend\controllers;


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
        catch (Exception $e)
        {
            throw new NotFoundHttpException();
        }

        return $this->render('view',[
            'task'   => $task,
        ]);
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}