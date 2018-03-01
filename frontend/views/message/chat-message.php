<?php

use common\models\entities\MessageEntity;
use yii\helpers\Html;

/**
 * @var MessageEntity $message
 */
?>

<div class="message <?= ($message->getIsSender()) ? null : 'message-from-companion' ?>">
    <div class="message-img-block"><?= Html::img($message->getSelf()->getAvatarAlias(), ['class' => 'comment-avatar']) ?></div>
    <div class="chat-message-content">
        <p><?= $message->getContent(true) ?></p>
    </div>
    <div class="message-date">
        <span><?= $message->getCreationDate() ?></span>
    </div>
</div>
