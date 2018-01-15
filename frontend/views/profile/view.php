<?php

use common\models\entities\UserEntity;
use yii\helpers\Html;

/**
 * @var UserEntity $user
 */
?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <div>
                <?= Html::img($user->getAvatarAlias(), ['class' => 'avatar']) ?>
            </div>
            <div class="information">
                <h4 class="inf-block">Логин: <?= $user->getUsername() ?></h4>
                <h4 class="inf-block">ФИО: <?= $user->getFio() ?></h4>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= Html::a('Написать сообщение', ['/message/chat', 'companionId' => $user->getId()], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>