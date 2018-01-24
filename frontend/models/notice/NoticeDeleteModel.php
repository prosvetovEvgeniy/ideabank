<?php

namespace frontend\models\notice;


use common\models\repositories\NoticeRepository;
use common\models\entities\NoticeEntity;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class NoticeDeleteModel
 * @package frontend\models\notice
 *
 *
 */
class NoticeDeleteModel extends Model
{
    public $id;
    public $recipientId;

    public function rules()
    {
        return [
          [['id', 'recipientId'], 'required'],
          [['id', 'recipientId'], 'integer']
        ];
    }

    public function delete()
    {
        if(!$this->validate())
        {
            return false;
        }

        $notice = $this->getNotice();

        if($notice->getViewed() || !$notice)
        {
            return false;
        }

        try
        {
            NoticeRepository::instance()->delete($notice);

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * @return NoticeEntity|null
     */
    private function getNotice()
    {
        return NoticeRepository::instance()->findOne([
            'id' => $this->id,
            'recipient_id' => $this->recipientId
        ]);
    }
}