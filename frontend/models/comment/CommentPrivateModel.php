<?php

namespace frontend\models\comment;


use common\components\facades\CommentFacade;
use common\models\entities\ParticipantEntity;
use common\models\repositories\CommentRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class CommentPrivateModel
 * @package frontend\models\comment
 *
 * @property integer $id
 */
class CommentPrivateModel extends Model
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
     */
    public function update()
    {
        if(!$this->validate())
        {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if(!$comment || $comment->getDeleted() || $comment->getPrivate())
        {
            return false;
        }

        if(!Yii::$app->user->is(ParticipantEntity::ROLE_MANAGER, $comment->getTask()->getProjectId()))
        {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            CommentFacade::makePrivate($comment);

            $transaction->commit();
            return true;
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            return false;
        }
    }
}