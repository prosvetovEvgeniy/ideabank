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
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TaskSearchForm();

        if (!$searchModel->load(Yii::$app->request->queryParams) || !$searchModel->validate()) {
            throw new NotFoundHttpException();
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

        $dataProvider = new EntityDataProvider([
            'condition' => [
                'task_id' => $task->getId()
            ],
            'repositoryInstance' => CommentViewRepository::instance(),
            'pagination' => [
                'pageSize' => CommentViewRepository::COMMENTS_PER_PAGE
            ]
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
            'isManager'    => Yii::$app->user->isManager($task->getProjectId())
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