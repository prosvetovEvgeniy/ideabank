<?php

namespace frontend\models\comment;

use yii\base\Model;
use common\models\entities\CommentLikeEntity;
use common\models\repositories\comment\CommentLikeRepository;
use yii\db\Exception;

/**
 * Class CommentVoteModel
 * @package frontend\models
 *
 * @property int  $userId
 * @property int  $commentId
 * @property bool $liked
 */
class CommentVoteModel extends Model
{
    public const SCENARIO_DELETE = 'delete';

    public $userId;
    public $commentId;
    public $liked;

    public function rules()
    {
        return [
            [['userId', 'commentId', 'liked'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['userId', 'commentId'], 'required', 'on' => self::SCENARIO_DELETE],
            [['userId', 'commentId'], 'integer'],
            [['liked'], 'boolean'],
        ];
    }

    public function add()
    {
        if (!$this->validate() || $this->recordExist()) {
            return false;
        }

        $commentLike = new CommentLikeEntity($this->commentId, $this->userId, $this->liked);

        try {
            CommentLikeRepository::instance()->add($commentLike);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $commentLike = $this->getCommentLike();

        if (!$commentLike) {
            return false;
        }

        try {
            CommentLikeRepository::instance()->delete($commentLike);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function reverse()
    {
        if (!$this->validate()) {
            return false;
        }

        $commentLike = $this->getCommentLike();

        if (!$commentLike) {
            return false;
        }

        ($commentLike->getLiked() === true) ? $commentLike->dislike() : $commentLike->like();

        try {
            CommentLikeRepository::instance()->update($commentLike);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return CommentLikeEntity|null
     */
    public function getCommentLike()
    {
        return CommentLikeRepository::instance()->findOne([
            'comment_id' => $this->commentId,
            'user_id'    => $this->userId
        ]);
    }

    /**
     * @return bool
     */
    private function recordExist()
    {
        return ($this->getCommentLike() !== null) ? true : false ;
    }
}