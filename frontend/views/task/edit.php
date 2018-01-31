<?php

use yii\widgets\ActiveForm;
use frontend\models\task\EditTaskForm;
use yii\helpers\Html;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
use frontend\assets\TaskFileDeleteAsset;


/**
 * @var EditTaskForm $model
 * @var TaskEntity   $task
 */

TaskFileDeleteAsset::register($this);

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

<?= $form->field($model, 'projectId')->dropDownList($model->getProjects()) ?>

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

<?= Html::a('Перейти к задаче', ['task/view', 'id' => $task->getId()], ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>
