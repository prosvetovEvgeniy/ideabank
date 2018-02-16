<?php

namespace frontend\models\comment;


use common\components\facades\CommentFacade;
use common\models\entities\ParticipantEntity;
use common\models\repositories\comment\CommentRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class CommentEditModel
 * @package frontend\models\comment
 *
 * @property int    $id
 * @property string $content
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
    public function update()
    {
        if(!$this->validate())
        {
            return false;
        }

        $comment = CommentRepository::instance()->findOne(['id' => $this->id]);

        if(!$comment || $comment->getDeleted())
        {
            return false;
        }

        if(!Yii::$app->user->is(ParticipantEntity::ROLE_MANAGER, $comment->getTask()->getProjectId()))
        {
            return false;
        }

        $comment->setContent($this->content);

        try
        {
            CommentFacade::editComment($comment);

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}