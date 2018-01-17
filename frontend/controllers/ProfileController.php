<?php

namespace frontend\controllers;


use common\components\dataproviders\EntityDataProvider;
use common\models\repositories\AuthAssignmentRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\UserRepository;
use frontend\models\profile\ChangeOwnDataForm;
use frontend\models\profile\ChangePasswordForm;
use frontend\models\profile\DeleteParticipantModel;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    public function actionChangeOwnData()
    {
        $model = new ChangeOwnDataForm();

        if($model->load(Yii::$app->request->post()))
        {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if($model->update())
            {
                Yii::$app->session->setFlash('ownDataChanged', 'Вы успешно изменили свои данные');
            }
        }

        $avatar = Yii::$app->user->identity->getUser()->getAvatarAlias();

        return $this->render('change-own-data', [
            'model'  => $model,
            'avatar' => $avatar
        ]);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();

        if($model->load(Yii::$app->request->post()) && $model->update())
        {
            $model = new ChangePasswordForm();

            Yii::$app->session->setFlash('passwordChanged', 'Пароль успешно изменен');
        }

        return $this->render('change-password', [
            'model' => $model
        ]);
    }

    public function actionMyProjects()
    {
        $dataProvider = new EntityDataProvider([
            'condition' => ParticipantRepository::instance()->getConditionOnParticipantsInProjects(),
            'repositoryInstance' => ParticipantRepository::instance(),
            'pagination' => [
                'pageSize' => 25
            ],
        ]);

        return $this->render('my-projects',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id)
    {
        $user = UserRepository::instance()->findOne(['id' => $id]);

        if(!$user)
        {
            throw new BadRequestHttpException();
        }

        return $this->render('view', [
            'user' => $user
        ]);
    }


    //###################### AJAX ACTIONS ######################


    //удаление записи в таблице participant
    public function actionDeleteParticipant()
    {
        $model = new DeleteParticipantModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->delete())
        {
            throw new BadRequestHttpException();
        }
    }
}