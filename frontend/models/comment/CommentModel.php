<?php

namespace frontend\models\comment;


use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use common\models\repositories\CommentRepository;
use common\models\repositories\CommentViewRepository;
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
 * @property string $url
 *
 * @property CommentEntity $comment
 */
class CommentModel extends Model
{
    public $parentId;
    public $taskId;
    public $content;
    public $url; //url по которому нужно формировать ссылку

    //сущность сохраненного комментария
    protected $comment;


    public function rules()
    {
        return [
            [['content', 'taskId', 'url'], 'required'],
            [['content'], 'string', 'length' => [1, 2000]],
            [['content'], 'trim'],
            [['url'], 'string'],
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
     * @param $params
     *
     * Проверка на то, что родитель комментария указан именно для текущей задачи,
     * а не какой-то другой. Если комментарий с таким id отсутствует у текущей задачи,
     * то валидация не происходит
     */
    public function checkOnEntrance($attribute, $params)
    {
        $parentComment = Comment::findOne(['id' => $this->$attribute, 'task_id' => $this->taskId]);

        if(!$parentComment)
        {
            $this->addError($attribute);
        }
    }

    public function attributeLabels()
    {
        return [
            'content'  => 'Текст'
        ];
    }

    public function save()
    {
        if(!$this->validate())
        {
            return false;
        }

        $senderId = Yii::$app->user->identity->getUserId();

        $comment = new CommentEntity($this->taskId, $senderId, $this->content, $this->parentId);

        /*//если есть упоминания о других пользователях
        preg_match_all("/@\w+/",$this->content, $matches);

        if(!empty($matches))
        {

        }*/

        try
        {
            $this->comment = CommentRepository::instance()->add($comment);
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * Возращает url по которому будет находится новый созданный комментарий.
     * Пример: 'http://ideabank.local/task/view?taskId=1&page=2&per-page=30#44'
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url . '?taskId=' . $this->taskId .
               '&page=' . $this->calculatePageNumber() .
               '&per_page=' . CommentViewRepository::COMMENTS_PER_PAGE .
               '#' . $this->comment->getId();
    }

    /**
     * Расчитывает номер страницы на которой будет отображен созднный комментарий
     *
     * @return float
     */
    private function calculatePageNumber()
    {
        $count = CommentRepository::instance()->getCountRecordsBeforeComment($this->comment);
        $perPage = CommentViewRepository::COMMENTS_PER_PAGE;

        return floor($count/$perPage) + 1;
    }
}
