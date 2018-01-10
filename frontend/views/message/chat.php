<?php

use frontend\assets\SubMenuAsset;
use common\components\widgets\MessageMenuWidget;
use frontend\assets\ChatAsset;
use frontend\models\message\SendMessageForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\entities\MessageEntity;
use common\models\entities\UserEntity;

SubMenuAsset::register($this);
ChatAsset::register($this);

/**
 * @var MessageEntity[] $messages
 * @var SendMessageForm $model
 * @var UserEntity $companion
 */
?>

<div class="row">

    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= MessageMenuWidget::widget() ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-9 somesome">
        <div class="chat-block">
            <div class="chat-header">
                <div class="companion-block">
                    <a href="#">
                        <img class="comment-avatar" src="/images/stub-img.png">
                    </a>
                    <a href=""><?= $companion->getUsername() ?></a>
                </div>
                <button type="button" class="btn btn-outline-danger">Удалить диалог</button>
            </div>
            <div class="chat">

                <?php foreach ($messages as $message) : ?>

                    <div class="message <?= ($message->getIsSender()) ? null : 'message-from-companion' ?>">
                        <div class="message-img-block"><img class="comment-avatar" src="/images/stub-img.png"></div>
                        <div class="chat-message-content">
                            <p><?= $message->getContent() ?></p>
                        </div>
                        <div class="message-date">
                            <span><?= $message->getCreationDate() ?></span>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            <div class="message-send-form">
                <?php
                $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'options' => [
                        'class' => 'send-message-form'
                    ]
                ]);
                ?>

                <?= $form->field($model, 'content')->textarea()->label(false) ?>

                <?= $form->field($model, 'companionId')->hiddenInput(['value' => $companion->getId()])->label(false) ?>

                <?= Html::submitButton('Ответить',['class' => 'btn btn-primary']); ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>