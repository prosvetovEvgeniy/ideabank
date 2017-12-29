<?php

use frontend\assets\SubMenuAsset;
use common\components\widgets\MessageMenuWidget;
use common\models\entities\MessageEntity;
use common\components\dataproviders\EntityDataProvider;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use frontend\assets\MessageDeleteAsset;

SubMenuAsset::register($this);
MessageDeleteAsset::register($this);

/**
 * @var MessageEntity $sentMessage
 * @var EntityDataProvider $dataProvider
 */
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= MessageMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">
        <div class="dialogs-block">

            <table class="table dialogs">
                <tbody>

                <?php foreach ($dataProvider->getModels() as $sentMessage) :?>
                    <tr class="dialog-row" data-message-id="<?= $sentMessage->getId() ?>">
                        <td><i class="glyphicon glyphicon-envelope"></i></td>
                        <td class="message-content" onclick="window.location.href='/message/chat?companionId=<?= $sentMessage->getCompanionId() ?>'; return false"><p><?= $sentMessage->getContent() ?></p></td>
                        <td><?= $sentMessage->getCreationDate() ?></td>
                        <td><?= Html::a( $sentMessage->getCompanion()->getUsername(), '#') ?></td>
                        <td><i class="glyphicon glyphicon-trash delete-button delete-message"></i></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

            <?=
            LinkPager::widget([
                'pagination' => $dataProvider->getPagination()
            ])
            ?>
        </div>
    </div>
</div>