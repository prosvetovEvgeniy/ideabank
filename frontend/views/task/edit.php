<?php

use yii\widgets\ActiveForm;
use frontend\models\task\EditTaskForm;
use yii\helpers\Html;

/**
 * @var EditTaskForm $model
 */
?>

<?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'content')->textarea() ?>

<?php //echo $form->field($model, 'files')->fileInput(['multiple' => true, 'accept' => '*']) ?>

<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>
