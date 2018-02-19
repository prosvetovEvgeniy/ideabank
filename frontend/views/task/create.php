<?php

use frontend\models\task\CreateTaskForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\entities\ProjectEntity;
use common\components\helpers\ProjectHelper;

/**
 * @var CreateTaskForm  $model
 * @var ProjectEntity[] $projects;
 */

$this->title = 'Создать задачу'
?>

<?php if(empty($projects)): ?>

    <h2>Вы не участвуете ни в одном проекте</h2>

<?php else: ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'create-task-form'
    ]);
    ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

    <?= $form->field($model, 'projectId')->dropDownList(ProjectHelper::getProjectItems($projects)) ?>

    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>

    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

<?php endif; ?>
