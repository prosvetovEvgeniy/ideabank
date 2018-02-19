<?php

namespace frontend\models\participant;


use common\components\facades\ParticipantFacade;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Exception;

/**
 * Class BlockParticipantModel
 * @package frontend\models\participant
 *
 * @property int $id
 */
class BlockParticipantModel extends Model
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
     */
    public function save()
    {
        if(!$this->validate()){
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->id]);

        if(!$participant || $participant->getBlocked() || $participant->getDeleted()){
            return false;
        }
        
        $participantFacade = new ParticipantFacade();

        try{
            $participantFacade->block($participant);

            return true;
        }
        catch (Exception $e){
            return false;
        }
    }
}