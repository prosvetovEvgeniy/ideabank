<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;
use yii\widgets\ActiveForm;
use frontend\models\profile\ChangeOwnDataForm;
use yii\helpers\Html;

SubMenuAsset::register($this);

/**
 * @var ChangeOwnDataForm $model
 * @var string $avatar
 */
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>

    <div class="col-lg-8 col-md-8 col-sm-9">

        <?php if(Yii::$app->session->hasFlash('ownDataChanged')): ?>

            <div class="alert alert-success" role="alert">
                <p><?= Yii::$app->session->getFlash('ownDataChanged') ?></p>
            </div>

        <?php endif; ?>


        <?php
        $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]);
        ?>

        <?= $form->field($model, 'username')->textInput(['value' => $model->username]) ?>

        <?= $form->field($model, 'email')->textInput(['value' => $model->email]) ?>

        <?= $form->field($model, 'firstName')->textInput(['value' => $model->firstName]) ?>

        <?= $form->field($model, 'secondName')->textInput(['value' => $model->secondName]) ?>

        <?= $form->field($model, 'lastName')->textInput(['value' => $model->lastName]) ?>

        <?= $form->field($model, 'phone')->textInput(['value' => $model->phone]) ?>

        <?= $form->field($model, 'avatar')->fileInput() ?>

        <?= Html::img($avatar,['class' => 'avatar']) ?>

        <?= $form->errorSummary($model); ?>

        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

