<?php

namespace frontend\models\notice;

use common\components\facades\NoticeFacade;
use common\models\repositories\notice\NoticeRepository;
use yii\base\Model;
use yii\db\Exception;
use Yii;

/**
 * Class NoticeDeleteModel
 * @package frontend\models\notice
 *
 * @property int $id
 */
class NoticeDeleteModel extends Model
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

        $notice = NoticeRepository::instance()->findOne([
            'id' => $this->id,
            'recipient_id' => Yii::$app->user->getId()
        ]);

        if (!$notice) {
            return false;
        }

        $noticeFacade = new NoticeFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $noticeFacade->deleteNotice($notice);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}