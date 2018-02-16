<?php

namespace common\models\repositories\task;


use common\components\helpers\FileHelper;
use common\models\activerecords\TaskFile;
use common\models\builders\TaskFileEntityBuilder;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
use common\models\interfaces\IRepository;
use GuzzleHttp\Psr7\UploadedFile;
use yii\db\Exception;
use Yii;

/**
 * Class TaskFileRepository
 * @package common\models\repositories
 *
 * @property TaskFileEntityBuilder $builderBehavior
 */
class TaskFileRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new TaskFileEntityBuilder();
    }


    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return TaskFileRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskFileEntity|null
     */
    public function findOne(array $condition)
    {
        $model = TaskFile::findOne($condition);

        if(!$model || $model->deleted)
        {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return TaskFileEntity[]
     */
    public function findAll(array $condition, int $limit = -1, int $offset = null, string $orderBy = null)
    {
        $models = TaskFile::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function add(TaskFileEntity $taskFile)
    {
        $model = new TaskFile();

        $this->builderBehavior->assignProperties($model, $taskFile);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save task_file with hash_name = ' . $taskFile->getHashName());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function update(TaskFileEntity $taskFile)
    {
        $model = TaskFile::findOne(['id' => $taskFile->getId()]);

        if(!$model)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $taskFile);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update task_file with id = ' . $taskFile->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function delete(TaskFileEntity $taskFile)
    {
        $model = TaskFile::findOne(['id' => $taskFile->getId()]);

        if(!$model)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task_file with id = ' . $taskFile->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) TaskFile::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param UploadedFile[] $files
     * @param TaskEntity     $task
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function saveFiles(array $files, TaskEntity $task)
    {
        foreach ($files as $file)
        {
            $fileHelper = new FileHelper($file->extension, self::instance());
            $hashName = $fileHelper->getHash('hash_name');

            $taskFile = new TaskFileEntity($task->getId(), $hashName, $file->name);

            $this->add($taskFile);

            //если файл не сохранился на диск, то выбрасываем исключение
            if (!$file->saveAs(TaskFileEntity::PATH_TO_FILE . $hashName))
            {
                throw new \yii\base\Exception();
            }
        }
    }
}