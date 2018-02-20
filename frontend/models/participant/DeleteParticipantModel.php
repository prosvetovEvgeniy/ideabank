<?php

namespace frontend\models\participant;

use common\components\facades\ParticipantFacade;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class DeleteParticipantModel
 * @package frontend\models\profile
 *
 * @property int $id
 */
class DeleteParticipantModel extends Model
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
     * @throws Exception
     * @throws \Throwable
     */
    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->id]);

        if (!$participant || $participant->getDeleted() || $participant->getBlocked()) {
            return false;
        }

        $participantFacade = new ParticipantFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $participantFacade->deleteParticipant($participant);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}