<?php

use frontend\models\task\CreateTaskForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\entities\ProjectEntity;
use yii\helpers\ArrayHelper;

/**
 * @var CreateTaskForm  $model
 * @var ProjectEntity[] $projects;
 */
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-task-form'
    ]);

    $projectItems = ArrayHelper::map($projects,
                                     function($project){ return $project->getId(); },
                                     function($project) { return $project->getName();});
?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

<?= $form->field($model, 'projectId')->dropDownList($projectItems) ?>

<?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>

<?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>


<?php ActiveForm::end(); ?>
