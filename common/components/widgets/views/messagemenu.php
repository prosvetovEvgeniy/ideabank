<?php

use yii\helpers\Html;

?>

<h4 class="message-menu-title">Сообщения</h4>

<ul class="nav nav-pills nav-stacked sub-menu">
    <li><?= Html::a('Диалоги', '/message/dialog') ?></li>
    <li><?= Html::a('Входящие', '/message/inbox') ?></li>
    <li><?= Html::a('Отправленные', '/message/sent') ?></li>
    <li><?= Html::a('Собеседники', '/message/companions') ?></li>
</ul>
