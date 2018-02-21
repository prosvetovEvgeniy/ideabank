<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;
use yii\widgets\ActiveForm;
use frontend\models\profile\ChangePasswordForm;
use yii\helpers\Html;

SubMenuAsset::register($this);

/**
 * @var ChangePasswordForm $model
 */

$this->title = 'Изменить пароль';
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">

        <?php if(Yii::$app->session->hasFlash('passwordChanged')): ?>

            <div class="alert alert-success" role="alert">
                <p><?= Yii::$app->session->getFlash('passwordChanged') ?></p>
            </div>

        <?php endif; ?>

        <?php
            $form = ActiveForm::begin();
        ?>

        <?= $form->field($model, 'oldPassword')->passwordInput() ?>

        <?= $form->field($model, 'newPassword')->passwordInput() ?>

        <?= $form->field($model, 'confirmNewPassword')->passwordInput() ?>

        <?= $form->errorSummary($model); ?>

        <div style="color:#999;margin:1em 0">
            <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>.
        </div>

        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end() ?>

    </div>
</div>

