<?php

use common\models\entities\UserEntity;
use yii\helpers\Html;

/**
 * @var UserEntity $user
 */
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="profile">
            <div>
                <?= Html::img($user->getAvatarAlias(), ['class' => 'avatar']) ?>
            </div>
            <div class="inf-block">
                <h4>Логин: <?= $user->getUsername() ?></h4>
            </div>
            <div class="inf-block">
                <h4>ФИО: <?= $user->getFio() ?></h4>
            </div>
            <div>
                <?= Html::a('Написать сообщение', ['/message/chat', 'companionId' => $user->getId()], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>