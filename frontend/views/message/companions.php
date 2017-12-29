<?php

use common\components\widgets\MessageMenuWidget;
use frontend\assets\SubMenuAsset;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\UserEntity;
use yii\widgets\LinkPager;

SubMenuAsset::register($this);

/**
 * @var EntityDataProvider $dataProvider
 * @var UserEntity $companion
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

                <?php foreach ($dataProvider->getModels() as $companion) :?>
                    <tr class="dialog-row" onclick="window.location.href='/message/chat?companionId=<?= $companion->getId() ?>'; return false"
                        data-companion-id="<?= $companion->getId() ?>">
                        <td><i class="glyphicon glyphicon-user"></i></td>
                        <td class="message-content"><p><?= $companion->getUsername() ?></p></td>
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
