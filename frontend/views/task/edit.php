<?php

use yii\widgets\ActiveForm;
use frontend\models\task\EditTaskForm;
use yii\helpers\Html;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
use frontend\assets\TaskFileDeleteAsset;
use yii\jui\DatePicker;
use common\components\helpers\TaskHelper;
use frontend\assets\TaskDeleteAsset;

/**
 * @var EditTaskForm $model
 * @var TaskEntity   $task
 */

TaskFileDeleteAsset::register($this);
TaskDeleteAsset::register($this);

$this->title = 'Редактировать задачу';
?>

<?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<?php if(Yii::$app->session->hasFlash('taskChanged')): ?>

    <div class="alert alert-success" role="alert">
        <p><?= Yii::$app->session->getFlash('taskChanged') ?></p>
    </div>

<?php endif; ?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'content')->textarea() ?>

<?php if(Yii::$app->user->isManager($task->getProjectId())): ?>

    <?php if($task->hasChildren()): ?>
        <?= $form->field($model, 'status')->dropDownList(TaskEntity::LIST_STATUSES_PRIVATE_TASK); ?>
    <?php else: ?>
        <?= $form->field($model, 'status')->dropDownList(TaskEntity::LIST_STATUSES); ?>
    <?php endif; ?>

    <?= $form->field($model, 'visibilityArea')->dropDownList(TaskEntity::LIST_VISIBILITY_AREAS) ?>

    <?= $form->field($model, 'plannedEndAt')->widget(DatePicker::className(), [
        'dateFormat' => $model::DATE_FORMAT,
        'options' => ['class' => 'form-control']
    ]) ?>

    <?php if($task->hasChildren()): ?>
        <p><b>Дочерние задачи</b></p>
        <?php foreach ($task->getChildren() as $child): ?>
            <?= Html::a($child->getTitle(), ['/task/view', 'id' => $child->getId()], ['class' => 'child-task']); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?= $form->field($model, 'parentId')->dropDownList(TaskHelper::getParentTasksItems($task), ['prompt' => 'Отсутствует']) ?>
    <?php endif; ?>

    <?php if($task->getAuthorId() !== Yii::$app->user->identity->getUserId()): ?>
        <p><b>Создал</b>: <?= Html::a($task->getAuthor()->getUsername(), ['/profile/view', 'id' => $task->getAuthorId()]) ?></p>
    <?php endif; ?>

<?php endif; ?>

<div class="task-files-block">

    <?php if(!empty($task->getFiles())): ?>
        <h4>Файлы</h4>
    <?php endif; ?>

    <?php
    /**
     * @var TaskFileEntity $image
     */
    foreach ($task->getImagesToTask() as $image) :
    ?>

        <div class="file">
            <i class="glyphicon glyphicon-remove delete-file-btn" data-file-id="<?= $image->getId()?>" data-task-id="<?= $image->getTaskId() ?>"></i>
            <?php
                $img = Html::img($image->getWebAlias(), ['class' => 'file-view']);
                echo Html::a($img, ['task-file/download', 'id' => $image->getId()], ['target' => '_blank']);
            ?>
        </div>

    <?php endforeach; ?>

    <?php
    /**
     * @var TaskFileEntity $file
     */
    foreach ($task->getFilesToTask() as $file) :
    ?>

        <div class="file">
            <i class="glyphicon glyphicon-remove delete-file-btn" data-file-id="<?= $file->getId() ?>" data-task-id="<?= $file->getTaskId() ?>"></i>
            <?php
                $fileStubImg = Html::img($file->getFileStub(), ['class' => 'file-view']);
                echo Html::a($fileStubImg, ['task-file/download', 'id' => $file->getId()], ['target' => '_blank', 'title' => $file->getOriginalName()]);
            ?>
        </div>

    <?php endforeach; ?>
</div>


<?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>

<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

<button class="btn btn-danger delete-task-btn" data-task-id="<?= $task->getId() ?>">Удалить</button>

<?php ActiveForm::end() ?>
