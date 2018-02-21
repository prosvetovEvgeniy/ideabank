<?php

use common\models\entities\MessageEntity;

/**
 * @var MessageEntity $message
 */
?>

<div class="message <?= ($message->getIsSender()) ? null : 'message-from-companion' ?>">
    <div class="message-img-block"><img class="comment-avatar" src="/images/stub-img.png"></div>
    <div class="chat-message-content">
        <p><?= $message->getContent(true) ?></p>
    </div>
    <div class="message-date">
        <span><?= $message->getCreationDate() ?></span>
    </div>
</div>
