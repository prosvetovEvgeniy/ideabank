<?php

use common\models\entities\ProjectEntity;
use yii\widgets\ActiveForm;
use common\models\searchmodels\project\ParticipantSearchForm;
use common\components\helpers\ProjectHelper;
use yii\helpers\Html;

/**
 * @var ProjectEntity $project
 * @var ParticipantSearchForm $model
 */

//$this->title = 'Пользователи проекта ' . $project->getName();
?>


<div class="row">
    <div class="col-lg-3">
        <?php
        $form = ActiveForm::begin([
            'action' => ['participants'],
            'method' => 'get',
            'options' => [
                'class' => 'form-vertical',
            ],
        ]);
        ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'projectId')->dropDownList(ProjectHelper::getProjectForManagerItems()) ?>

        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>