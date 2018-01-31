<?php

use yii\widgets\ActiveForm;
use common\models\searchmodels\task\TaskEntitySearch;

/* @var $this yii\web\View */
/* @var $model \common\models\searchmodels\task\TaskEntitySearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-vertical',
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => $model->getAttributeLabel('title')])->label(false) ?>

    <?= $form->field($model, 'content')->textInput(['placeholder' => $model->getAttributeLabel('content')])->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(TaskEntitySearch::LIST_STATUSES_AS_TEXT)->label(false) ?>

    <?= $form->field($model, 'projectId')->hiddenInput()->label(false) ?>

    <button type="submit" class="btn btn-default">Найти</button>


    <?php ActiveForm::end(); ?>
</div>
<br><br>