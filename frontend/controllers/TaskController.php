<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\activerecords\TaskFile;
use common\models\entities\TaskFileEntity;
use common\models\entities\UserEntity;
use common\models\repositories\CommentViewRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\ProjectRepository;
use common\models\repositories\TaskFileRepository;
use common\models\repositories\TaskRepository;
use common\models\searchmodels\TaskEntitySearch;
use frontend\models\comment\CommentForm;
use frontend\models\task\CreateTaskForm;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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
        /**
         * @var UserEntity $user
         */
        $user = Yii::$app->user->identity->getEntity();

        $projects = ProjectRepository::instance()->getProjectsForUser($user);

        $model = new CreateTaskForm();
        $model->authorId = $user->getId();

        $model->load(Yii::$app->request->post());

        if($model->validate())
        {
            $model->files = UploadedFile::getInstances($model, 'files');

            if($model->save())
            {
                return $this->redirect('/task/view?taskId=' . $model->getTaskId());
            }
        }

        return $this->render('create', [
            'model'    => $model,
            'projects' => $projects
        ]);
    }

    public function actionDownload(int $id)
    {
        $file = TaskFileRepository::instance()->findOne(['id' => $id]);

        if(!$file)
        {
            throw new NotFoundHttpException();
        }

        $headers = Yii::$app->response->getHeaders();

        $headers->set('Content-Type', $file->getMimeType());

        if($file->isImage())
        {
            $headers->set('Content-Disposition', 'inline');
        }
        else
        {
            $headers->set('ContentDisposition', 'attachment');
        }

        return Yii::$app->response->sendFile($file->getWebRootAlias(), $file->getOriginalName());
    }
}