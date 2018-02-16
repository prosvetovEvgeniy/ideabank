<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 07.02.18
 * Time: 18:25
 */

namespace frontend\models\comment;


use common\components\facades\CommentFacade;
use common\models\repositories\comment\CommentRepository;
use yii\base\Model;
use Yii;
use common\models\entities\ParticipantEntity;
use yii\db\Exception;

/**
 * Class CommentPublicModel
 * @package frontend\models\comment
 *
 * @property integer $id
 */
class CommentPublicModel extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    public function update()
    {
        if(!$this->validate())
        {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if(!$comment || $comment->getDeleted() || !$comment->getPrivate())
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
            CommentFacade::makePublic($comment);

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