<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\entities\UserEntity;
use common\models\repositories\CompanionRepository;
use common\models\repositories\DialogRepository;
use common\models\repositories\MessageRepository;
use common\models\repositories\UserRepository;
use frontend\models\message\DeleteMessageModel;
use frontend\models\message\SendMessageForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\web\UnauthorizedHttpException;

class MessageController extends Controller
{
    public function actionDialog()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id' => Yii::$app->user->identity->getUserId(),
                'deleted' => false
            ],
            'repositoryInstance' => DialogRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('dialog', ['dataProvider' => $dataProvider]);
    }

    public function actionInbox()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id'   => Yii::$app->user->identity->getUserId(),
                'is_sender' => false,
                'deleted'   => false
            ],
            'repositoryInstance' => MessageRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('inbox', ['dataProvider' => $dataProvider]);
    }

    public function actionSent()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id'   => Yii::$app->user->identity->getUserId(),
                'is_sender' => true,
                'deleted'   => false
            ],
            'repositoryInstance' => MessageRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ],
            'orderBy' => 'id DESC'
        ]);

        return $this->render('sent', ['dataProvider' => $dataProvider]);
    }

    public function actionCompanions()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => [
                'self_id'   => Yii::$app->user->identity->getUserId(),
            ],
            'repositoryInstance' => CompanionRepository::instance(),
            'pagination' => [
                'pageSize' => 20
            ],
            'orderBy' => 'companion_id ASC'
        ]);

        return $this->render('companions', ['dataProvider' => $dataProvider]);
    }

    /**
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionDeleteMessage()
    {
        if(Yii::$app->user->isGuest)
        {
            throw new UnauthorizedHttpException();
        }

        $model = new DeleteMessageModel();
        $model->scenario = DeleteMessageModel::SCENARION_DELETE_MESSAGE;
        $model->selfId = Yii::$app->user->identity->getUserId();

        $model->load(Yii::$app->request->post());

        if(!$model->validate() || !$model->deleteMessage())
        {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionDeleteDialog()
    {
        if(Yii::$app->user->isGuest)
        {
            throw new UnauthorizedHttpException();
        }

        $model = new DeleteMessageModel();
        $model->scenario = DeleteMessageModel::SCENARION_DELETE_DIALOG;

        $model->selfId = Yii::$app->user->identity->getUserId();

        $model->load(Yii::$app->request->post());

        if(!$model->validate() || !$model->deleteDialog())
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionChat(int $companionId)
    {
        if(Yii::$app->user->isGuest)
        {
            throw new UnauthorizedHttpException();
        }

        $companion = UserRepository::instance()->findOne(['id' => $companionId]);

        if(!$companion)
        {
            throw new BadRequestHttpException();
        }

        $messages = MessageRepository::instance()->findAll([
            'self_id'      => Yii::$app->user->identity->getUserId(),
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

    public function actionSend()
    {
        if(Yii::$app->user->isGuest)
        {
            throw new UnauthorizedHttpException();
        }

        $model = new SendMessageForm();
        $model->selfId = Yii::$app->user->identity->getUserId();

        $model->load(Yii::$app->request->post());

        if($model->validate() && $model->save())
        {
            Yii::$app->layout = false;

            return $this->render('chat-message', ['message' => $model->getSelfMessage()]);
        }

        throw new BadRequestHttpException();
    }
}