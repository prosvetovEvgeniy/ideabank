<?php

namespace frontend\controllers;


use common\models\repositories\AuthAssignmentRepository;
use common\models\repositories\ParticipantRepository;
use frontend\models\profile\ChangeOwnDataForm;
use frontend\models\profile\ChangePasswordForm;
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
        $participants = ParticipantRepository::instance()->getParticipantsInProjects();

        $deletedParticipants = ParticipantRepository::instance()->getDeletedParticipants();

        return $this->render('my-projects',[
            'participants'        => $participants,
            'deletedParticipants' => $deletedParticipants
        ]);
    }

    public function actionLeaveProject()
    {
        $participantId = Yii::$app->request->post('participantId');
        $participant = ParticipantRepository::instance()->findOne(['id' => $participantId]);
        $userId = Yii::$app->user->identity->getUser()->getId();

        if(!$participant || $participant->getDeleted() ||
            ($participant->getUserId() !== $userId) ||
            ($participant->getProjectId() === null && $participant->getCompanyId() === null))
        {
            throw new BadRequestHttpException();
        }

        try
        {
            ParticipantRepository::instance()->delete($participant);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionJoinToProject()
    {
        $participantId = Yii::$app->request->post('participantId');
        $participant = ParticipantRepository::instance()->findOne(['id' => $participantId]);
        $userId = Yii::$app->user->identity->getUser()->getId();

        if(!$participant || !$participant->getDeleted() ||
            ($participant->getUserId() !== $userId) ||
            ($participant->getProjectId() === null && $participant->getCompanyId() === null))
        {
            throw new BadRequestHttpException();
        }

        try
        {
            $participant->setDeleted(false);
            $participant->setDeletedAt();

            ParticipantRepository::instance()->update($participant);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    public function actionDeleteParticipant()
    {
        $participantId = Yii::$app->request->post('participantId');
        $participant = ParticipantRepository::instance()->findOne(['id' => $participantId]);
        $authAssignment = AuthAssignmentRepository::instance()->findOne(['user_id' => $participant->getId()]);
        $userId = Yii::$app->user->identity->getUser()->getId();

        if(!$participant || !$authAssignment || !$participant->getDeleted() ||
            ($participant->getUserId() !== $userId) ||
            ($participant->getProjectId() === null && $participant->getCompanyId() === null))
        {
            throw new BadRequestHttpException();
        }

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            AuthAssignmentRepository::instance()->delete($authAssignment);
            ParticipantRepository::instance()->deleteFromDb($participant);

            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollBack();

            throw new BadRequestHttpException();
        }
    }
}