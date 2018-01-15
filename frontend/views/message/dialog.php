<?php

use frontend\assets\SubMenuAsset;
use common\components\widgets\MessageMenuWidget;
use common\models\entities\MessageEntity;
use common\components\dataproviders\EntityDataProvider;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use frontend\assets\MessageDeleteAsset;
use common\models\repositories\MessageRepository;

SubMenuAsset::register($this);
MessageDeleteAsset::register($this);

/**
 * @var MessageEntity $dialog
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

                    <?php foreach ($dataProvider->getModels() as $dialog) :?>
                        <tr class="dialog-row" data-companion-id="<?= $dialog->getCompanionId() ?>"
                                               data-companion-username="<?= $dialog->getCompanion()->getUsername() ?>">
                            <td><i class="glyphicon glyphicon-envelope"></i></td>
                            <td class="message-content" onclick="window.location.href='/message/chat?companionId=<?= $dialog->getCompanionId() ?>'; return false">
                                <p><?= $dialog->getContent() ?></p>
                            </td>
                            <td>
                                <?php $unviewedMsgCount = MessageRepository::instance()->getUnviewedMsgCount($dialog->getSelf(), $dialog->getCompanion()); ?>

                                <?php if($unviewedMsgCount !== 0): ?>
                                    <div class="unviewed-messages">
                                        + <?= $unviewedMsgCount ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= $dialog->getCreationDate() ?></td>
                            <td><?= Html::a( $dialog->getCompanion()->getUsername(),  ['/profile/view', 'id' => $dialog->getCompanion()->getId()]) ?></td>
                            <td><i class="glyphicon glyphicon-trash delete-button delete-dialog"></i></td>
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