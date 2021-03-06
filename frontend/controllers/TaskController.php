<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\components\helpers\LinkHelper;
use common\models\repositories\comment\CommentViewRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\task\TaskRepository;
use common\models\searchmodels\task\TaskSearchForm;
use frontend\models\comment\CommentCreateForm;
use frontend\models\task\CreateTaskForm;
use frontend\models\task\DeleteTaskModel;
use frontend\models\task\EditTaskForm;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'edit', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'edit', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TaskSearchForm();

        if (!$searchModel->load(Yii::$app->request->queryParams) || !$searchModel->validate()) {
            throw new NotFoundHttpException();
        }

        if (Yii::$app->user->isBlocked($searchModel->projectId)) {
            throw new ForbiddenHttpException();
        }

        $dataProvider = $searchModel->search();

        $participants = ParticipantRepository::instance()->getRelationToProjects();

        return $this->render('index', [
            'dataProvider'   => $dataProvider,
            'searchModel'    => $searchModel,
            'currentProject' => $searchModel->getProject(),
            'participants'   => $participants
        ]);
    }

    public function actionView(int $id)
    {
        $task = TaskRepository::instance()->findOne(['id' => $id]);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        if (Yii::$app->user->participantHadBlockedRole($task->getProjectId())) {
            throw new ForbiddenHttpException();
        }

        if (Yii::$app->user->isBlocked($task->getProjectId())) {
            throw new ForbiddenHttpException();
        }

        if (($task->forRegistered() || $task->private()) && Yii::$app->user->isGuest) {
            throw new NotAcceptableHttpException();
        }

        if ($task->private() && !$task->own() && !Yii::$app->user->isManager($task->getProjectId())) {
            throw new NotAcceptableHttpException();
        }

        $dataProvider = new EntityDataProvider([
            'condition' => [
                'task_id' => $task->getId()
            ],
            'repositoryInstance' => CommentViewRepository::instance(),
            'pagination' => [
                'pageSize' => CommentViewRepository::COMMENTS_PER_PAGE
            ],
            'with' => ['sender', 'parent', 'task']
        ]);

        $model = new CommentCreateForm();
        $model->taskId = $task->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(LinkHelper::getLinkOnComment($model->getComment()));
        }

        return $this->render('view',[
            'task'         => $task,
            'dataProvider' => $dataProvider,
            'model'        => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new CreateTaskForm();
        $model->authorId = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post())) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if($model->save()) {
                return $this->redirect(LinkHelper::getLinkOnTask($model->getTask()));
            }
        }

        $projects = ProjectRepository::instance()->getProjectsForUser();

        return $this->render('create', [
            'model'    => $model,
            'projects' => $projects
        ]);
    }

    public function actionEdit(int $id)
    {
        $task = TaskRepository::instance()->findOne(['id' => $id]);

        if (!$task) {
            throw new BadRequestHttpException();
        }

        if (!Yii::$app->user->isUser($task->getProjectId())) {
            throw new NotAcceptableHttpException();
        }

        $model = new EditTaskForm($task);

        if (Yii::$app->user->isManager($task->getProjectId())){
            $model->scenario = EditTaskForm::SCENARIO_ADMIN_EDIT;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if($model->update()) {
                $this->redirect(['/task/view', 'id' => $model->getTask()->getId()]);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'task'  => $task
        ]);
    }

    public function actionDelete()
    {
        $model = new DeleteTaskModel();

        if (!$model->load(Yii::$app->request->post()) || !$model->delete()){
            throw new BadRequestHttpException();
        }

        return LinkHelper::getLinkOnActionTaskIndex($model->getTask()->getProject(), TaskSearchForm::STATUS_ALL);
    }
}