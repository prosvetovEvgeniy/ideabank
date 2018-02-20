<?php

namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\UserEntity;
use common\models\repositories\user\CompanionRepository;
use common\models\repositories\message\DialogRepository;
use common\models\repositories\message\MessageRepository;
use common\models\repositories\user\UserRepository;
use frontend\models\message\DeleteMessageModel;
use frontend\models\message\SendMessageForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class MessageController extends Controller
{
    public function actionDialog()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id' => Yii::$app->user->getId(),
                'deleted' => false
            ],
            'repositoryInstance' => DialogRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ],
            'orderBy' => 'id DESC'
        ]);

        return $this->render('dialog', ['dataProvider' => $dataProvider]);
    }

    public function actionCompanions()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id'   => Yii::$app->user->getId(),
            ],
            'repositoryInstance' => CompanionRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ],
            'orderBy' => 'companion_id ASC'
        ]);

        return $this->render('companions', ['dataProvider' => $dataProvider]);
    }

    public function actionChat(int $companionId)
    {
        /**
         * @var UserEntity $self
         */
        $self = Yii::$app->user->getId();

        $companion = UserRepository::instance()->findOne(['id' => $companionId]);

        if (!$companion) {
            throw new BadRequestHttpException();
        }

        /**
         * при заходе в чат делаем все сообщения просмотренными
         */
        MessageRepository::instance()->viewAll([
            'self_id'      => $self->getId(),
            'companion_id' => $companion->getId()
        ]);

        $messages = MessageRepository::instance()->findAll([
            'self_id'      => $self->getId(),
            'companion_id' => $companionId,
            'deleted'      => false
        ], -1);

        $model = new SendMessageForm();

        return $this->render('chat', [
            'messages'    => $messages,
            'model'       => $model,
            'companion'   => $companion
        ]);
    }


    //################ AJAX ACTIONS ################


    public function actionDeleteMessage()
    {
        $model = new DeleteMessageModel();
        $model->scenario = DeleteMessageModel::SCENARIO_DELETE_MESSAGE;
        $model->selfId = Yii::$app->user->getId();

        $model->load(Yii::$app->request->post());

        if (!$model->validate() || !$model->delete()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionDeleteDialog()
    {
        $model = new DeleteMessageModel();
        $model->scenario = DeleteMessageModel::SCENARIO_DELETE_DIALOG;

        $model->selfId = Yii::$app->user->getId();

        $model->load(Yii::$app->request->post());

        if (!$model->validate() || !$model->deleteDialog()) {
            throw new BadRequestHttpException();
        }
    }

    public function actionSend()
    {
        $model = new SendMessageForm();
        $model->selfId = Yii::$app->user->getId();

        $model->load(Yii::$app->request->post());

        if ($model->validate() && $model->save()) {
            Yii::$app->layout = false;

            return $this->render('chat-message', ['message' => $model->getSelfMessage()]);
        }

        throw new BadRequestHttpException();
    }
}