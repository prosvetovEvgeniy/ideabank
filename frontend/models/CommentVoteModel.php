<?php

namespace frontend\models;


use common\models\activerecords\CommentLike;
use yii\base\Model;

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
    public $userId;
    public $commentId;
    public $liked;

    public function rules()
    {
        return [
            [['userId', 'commentId', 'liked'], 'required'],
            [['userId', 'commentId'], 'integer'],
            [['liked'], 'string'],
            [['liked'], 'filter', 'filter' => function($value){
                return ($value === 'true') ? true : false;
            }]
        ];
    }

    /**
     * @return bool
     */
    public function recordExist()
    {
        $commentLike = CommentLike::findOne([
            'comment_id' => $this->commentId,
            'user_id'    => $this->userId,
            'liked'      => $this->liked
        ]);

        if($commentLike !== null)
        {
            return true;
        }

        return false;
    }
}