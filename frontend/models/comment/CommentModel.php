<?php

namespace frontend\models\comment;


use common\components\helpers\NoticeHelper;
use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use common\models\entities\NoticeEntity;
use common\models\repositories\CommentRepository;
use common\models\repositories\CommentViewRepository;
use common\models\repositories\NoticeRepository;
use yii\base\Model;
use common\models\activerecords\Task;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;

/**
 * Class CommentForm
 * @package frontend\models
 *
 * @property int $parentId
 * @property int $taskId
 * @property string $content
 *
 * @property string $link
 *
 * @property CommentEntity $comment
 */
class CommentModel extends Model
{
    public $parentId;
    public $taskId;
    public $content;

    //сущность сохраненного комментария
    private $comment;

    //ссылка для перехода по новому комментарию (кешируется)
    private $link;


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

        $noticedUsers = NoticeHelper::getNoticedUsers($this->content);

        //если есть упоминания открываем транзакцию
        if($noticedUsers) { Yii::$app->db->beginTransaction(); }

        try
        {
            $this->comment = CommentRepository::instance()->add($comment);

            //записываем упоминания в бд если они есть
            if(!empty($noticedUsers))
            {
                foreach ($noticedUsers as $noticedUser)
                {
                    NoticeRepository::instance()->add(
                        new NoticeEntity(
                            $noticedUser->getId(),
                            $this->comment->getContent(),
                            $this->getLink(),
                            $this->comment->getSenderId()
                        )
                    );
                }

                Yii::$app->db->transaction->commit();
            }

            return true;
        }
        catch (Exception $e)
        {
            if($noticedUsers) { Yii::$app->db->transaction->rollBack(); }

            return false;
        }
    }

    /**
     * Возращает ссылку по которой будет находится новый созданный комментарий.
     * Пример: 'http://ideabank.local/task/view?taskId=1&page=2&per-page=30#44'
     *
     * @return string
     */
    public function getLink()
    {
        if($this->link === null)
        {
            //из класс yii\data\Pagination получаем названия GET-параметров (page, per-page)
            $pagination = new Pagination();

            $this->link = Yii::$app->urlManager->createAbsoluteUrl([
                Yii::$app->request->pathInfo,
                'taskId' => $this->taskId,
                $pagination->pageParam => $this->calculatePageNumber(),
                $pagination->pageSizeParam => CommentViewRepository::COMMENTS_PER_PAGE,
                '#' => $this->comment->getId()
            ]);
        }

        return $this->link;
    }

    /**
     * Расчитывает номер страницы на которой будет отображен созданный комментарий
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
