<?php

namespace frontend\models\comment;

use common\components\facades\CommentFacade;
use common\models\entities\AuthAssignmentEntity;
use common\models\repositories\comment\CommentRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class CommentEditModel
 * @package frontend\models\comment
 *
 * @property int    $id
 */
class CommentDeleteModel extends Model
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
    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if (!$comment || $comment->getDeleted()) {
            return false;
        }

        if (!Yii::$app->user->isManager($comment->getTask()->getProjectId())) {
            return false;
        }

        $commentFacade = new CommentFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $commentFacade->deleteComment($comment);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}