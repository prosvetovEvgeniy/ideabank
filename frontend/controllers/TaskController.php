<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\components\helpers\LinkHelper;
use common\models\entities\ParticipantEntity;
use common\models\repositories\CommentViewRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\ProjectRepository;
use common\models\repositories\TaskRepository;
use common\models\searchmodels\task\TaskEntitySearch;
use frontend\models\comment\CommentEditModel;
use frontend\models\comment\CommentCreateForm;
use frontend\models\task\CreateTaskForm;
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
        $searchModel = new TaskEntitySearch();

        if(!$searchModel->load(Yii::$app->request->queryParams) || !$searchModel->validate())
        {
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
                'pageSize' => CommentViewRepository::COMMENTS_PER_PAGE
            ]
        ]);

        $model = new CommentCreateForm();
        $model->taskId = $task->getId();

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(LinkHelper::getLinkOnComment($model->getComment()));
        }

        return $this->render('view',[
            'task'         => $task,
            'dataProvider' => $dataProvider,
            'model'        => $model,
            'isManager'    => Yii::$app->user->is(ParticipantEntity::ROLE_MANAGER, $task->getProjectId())
        ]);
    }

    public function actionCreate()
    {
        $model = new CreateTaskForm();
        $model->authorId = Yii::$app->user->identity->getUserId();

        if($model->load(Yii::$app->request->post()))
        {
            $model->files = UploadedFile::getInstances($model, 'files');

            if($model->save())
            {
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

        if(!$task)
        {
            throw new BadRequestHttpException();
        }

        $model = new EditTaskForm($task);

        if($model->load(Yii::$app->request->post()))
        {
            $model->files = UploadedFile::getInstances($model, 'files');

            if($model->save())
            {
                Yii::$app->session->setFlash('taskChanged', 'Задача успешно обновлена');
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'task'  => $task
        ]);
    }

    public function actionEditComment()
    {
        $model = new CommentEditModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->edit())
        {
            throw new BadRequestHttpException();
        }
    }
}