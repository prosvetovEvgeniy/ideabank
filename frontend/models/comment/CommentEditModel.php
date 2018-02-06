<?php

namespace frontend\models\comment;


use common\models\entities\ParticipantEntity;
use common\models\repositories\CommentRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class CommentEditModel
 * @package frontend\models\comment
 *
 * @property int    $id
 * @property string $content
 * @property int    $userId
 */
class CommentEditModel extends Model
{
    public $id;
    public $content;

    public function rules()
    {
        return [
            [['id', 'content'], 'required'],
            [['id'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @return bool
     */
    public function edit()
    {
        if(!$this->validate())
        {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if(!$comment)
        {
            return false;
        }

        if(!Yii::$app->user->is(ParticipantEntity::ROLE_MANAGER, $comment->getTask()->getProjectId()))
        {
            return false;
        }

        try
        {
            $comment->setContent($this->content);

            CommentRepository::instance()->update($comment);

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}