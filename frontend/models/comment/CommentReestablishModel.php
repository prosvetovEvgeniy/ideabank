<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 07.02.18
 * Time: 16:44
 */

namespace frontend\models\comment;


use common\components\facades\CommentFacade;
use common\models\entities\AuthAssignmentEntity;
use common\models\repositories\comment\CommentRepository;
use yii\base\Model;
use yii\db\Exception;
use Yii;

/**
 * Class CommentReestablishModel
 * @package frontend\models\comment
 *
 * @property integer $id
 */
class CommentReestablishModel extends Model
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
     * @throws \Exception
     */
    public function update()
    {
        if(!$this->validate()) {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if(!$comment || !$comment->getDeleted()) {
            return false;
        }

        if(!Yii::$app->user->is(AuthAssignmentEntity::ROLE_MANAGER, $comment->getTask()->getProjectId())) {
            return false;
        }

        $commentFacade = new CommentFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $commentFacade->reestablishComment($comment);

            $transaction->commit();
            return true;
        }
        catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}