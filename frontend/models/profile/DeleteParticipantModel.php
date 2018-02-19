<?php

namespace frontend\models\profile;

use common\models\repositories\rbac\AuthAssignmentRepository;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class DeleteParticipantModel
 * @package frontend\models\profile
 *
 * @property int $participantId
 */
class DeleteParticipantModel extends Model
{
    public $participantId;

    public function rules()
    {
        return [
            [['participantId'], 'required'],
            [['participantId'], 'integer']
        ];
    }

    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->participantId]);
        $authAssignment = AuthAssignmentRepository::instance()->findOne(['user_id' => $participant->getId()]);
        $userId = Yii::$app->user->identity->getUser()->getId();

        if (!$participant || $participant->getDeleted() ||
            ($participant->getUserId() !== $userId) ||
            ($participant->getProjectId() === null && $participant->getCompanyId() === null))
        {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($authAssignment) {
                AuthAssignmentRepository::instance()->delete($authAssignment);
            }

            ParticipantRepository::instance()->delete($participant);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}