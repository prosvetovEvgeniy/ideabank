<?php

namespace frontend\models\participant;

use common\components\facades\ParticipantFacade;
use common\components\helpers\ParticipantHelper;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Exception;
use Yii;

/**
 * Class AddParticipantModel
 * @package frontend\models\participant
 *
 * @property int $id
 */
class AddParticipantModel extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->id]);

        if (!$participant ||
            $participant->getBlocked() ||
            $participant->getDeleted())
        {
            return false;
        }

        if (!Yii::$app->user->isManager($participant->getProjectId())) {
            return false;
        }

        $participantFacade = new ParticipantFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $participant = $participantFacade->addParticipant($participant);

            if (!ParticipantHelper::instance()->addOrUpdateRoleCache($participant)) {
                throw new Exception();
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}