<?php

use yii\helpers\Html;

?>

<h4 class="message-menu-title">Профиль</h4>

<ul class="nav nav-pills nav-stacked message-menu sub-menu">
    <li><?= Html::a('Личные данные ', '/profile/change-own-data') ?></li>
    <li><?= Html::a('Изменить пароль', '/profile/change-password') ?></li>
    <li><?= Html::a('Мои проекты', '/profile/my-projects') ?></li>
</ul>
