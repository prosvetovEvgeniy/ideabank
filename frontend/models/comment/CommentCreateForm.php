<?php

namespace frontend\models\comment;

use common\components\facades\CommentFacade;
use common\components\helpers\NoticeHelper;
use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use common\models\repositories\comment\CommentRepository;
use yii\base\Model;
use common\models\activerecords\Task;
use Yii;
use yii\db\Exception;

/**
 * Class CommentForm
 * @package frontend\models
 *
 * @property int $parentId
 * @property int $taskId
 * @property string $content
 *
 * @property CommentEntity $comment
 * @property NoticeHelper $noticeHelper
 */
class CommentCreateForm extends Model
{
    public $parentId;
    public $taskId;
    public $content;

    //сущность сохраненного комментария
    private $comment;

    public function rules()
    {
        return [
            [['content', 'taskId'], 'required'],
            [['content'], 'string', 'length' => [1, 2000]],
            [['content'], 'trim'],
            [['taskId'], 'exist', 'targetClass' => Task::className(), 'targetAttribute' => ['taskId' => 'id']],
            [['parentId', 'taskId'], 'integer'],
            [['parentId'], 'checkOnEntrance'],
            [['parentId'], 'filter', 'filter' => function($value) {
                //если parentId не пустой, то преобразовать в int
                return ($value !== '') ? (int) $value : null;
            }]
        ];
    }

    /**
     * @param $attribute
     *
     * Проверка на то, что родитель комментария указан именно для текущей задачи,
     * а не какой-то другой. Если комментарий с таким id отсутствует у текущей задачи,
     * то валидация не происходит
     */
    public function checkOnEntrance($attribute)
    {
        $parentComment = Comment::findOne(['id' => $this->$attribute, 'task_id' => $this->taskId]);

        if (!$parentComment) {
            $this->addError($attribute);
        }
    }

    public function attributeLabels()
    {
        return [
            'content'  => 'Текст'
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $comment = new CommentEntity(
            $this->taskId,
            Yii::$app->user->getId(),
            $this->content,
            $this->parentId
        );

        //если комментарий ссылается на удаленный или приватный
        if ($comment->getParentId()) {
            $parentComment = CommentRepository::instance()->findOne(['id' => $comment->getParentId()]);

            if (!$parentComment || $parentComment->getDeleted() || $parentComment->getPrivate()) {
                return false;
            }
        }

        $commentFacade = new CommentFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->comment = $commentFacade->createComment($comment);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @return CommentEntity
     */
    public function getComment()
    {
        return $this->comment;
    }
}
