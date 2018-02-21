<?php

use common\models\entities\TaskEntity;
use yii\helpers\Html;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\CommentEntity;
use yii\widgets\ActiveForm;
use frontend\models\comment\CommentCreateForm;
use frontend\assets\CommentAsset;
use frontend\assets\TaskAsset;
use yii\widgets\LinkPager;
use common\models\entities\TaskFileEntity;

TaskAsset::register($this);
CommentAsset::register($this);

/**
 * @var TaskEntity $task
 * @var EntityDataProvider $dataProvider
 * @var CommentCreateForm $model
 * @var boolean $isManager
 */

$comments = $dataProvider->getModels();

$pagination = $dataProvider->getPagination(); //пагинатор

$increment = $pagination->pageSize * $pagination->page;  //приращение номера комментария

$counter = 1; //счетчик для номера комментария

?>
    <div class="row">

        <div class="col-md-8">

            <div class="task"
                 data-task-id="<?= $task->getId() ?>"
                 data-current-user-liked-task="<?= $task->getCurrentUserLikedIt() ?>"
                 data-current-user-disliked-task="<?= $task->getCurrentUserDislikedIt() ?>">

                <h2 class="no-margin-top"><?= $task->getTitle() ?></h2>

                <p><?= $task->getContent(true) ?></p>

                <div class="footer-comment">
                    <span class="vote-up" title="Нравится">
                        <i class="glyphicon glyphicon-thumbs-up" data-user-guest="<?= Yii::$app->user->isGuest ?>"><?= $task->getAmountLikes() ?></i>
                    </span>
                    <span class="vote-down" title="Не нравится">
                        <i class="glyphicon glyphicon-thumbs-down" data-user-guest="<?= Yii::$app->user->isGuest ?>"><?= $task->getAmountDislikes() ?></i>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="task-files-block">

                        <?php if(!empty($task->getFiles())): ?>
                            <h4>Файлы</h4>
                        <?php endif; ?>

                        <?php
                        /**
                         * @var TaskFileEntity $image
                         */
                        foreach ($task->getImagesToTask() as $image) :
                            ?>

                            <div class="file">
                                <?php
                                $img = Html::img($image->getWebAlias(), ['class' => 'file-view']);
                                echo Html::a($img, ['task-file/download', 'id' => $image->getId()], ['target' => '_blank']);
                                ?>
                            </div>

                        <?php endforeach; ?>

                        <?php
                        /**
                         * @var TaskFileEntity $file
                         */
                        foreach ($task->getFilesToTask() as $file) :
                        ?>

                            <div class="file">
                                <?php
                                $fileStubImg = Html::img($file->getFileStub(), ['class' => 'file-view']);
                                echo Html::a($fileStubImg, ['task-file/download', 'id' => $file->getId()], ['target' => '_blank', 'title' => $file->getOriginalName(true)]);
                                ?>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <table class="table">
                <tbody>
                <tr>
                    <td>Проект</td>
                    <td>
                        <?= Html::a($task->getProject()->getName(true), ['project/view', 'id' => $task->getProject()->getId()]); ?>
                    </td>
                </tr>
                <tr>
                    <td>Создал</td>
                    <td>
                        <?php
                        if (Yii::$app->user->isGuest) {
                            echo Html::a($task->getAuthor()->getUsername(true), ['/site/login']);
                        } else {
                            if (Yii::$app->user->getId() === $task->getAuthorId()) {
                                echo Html::a($task->getAuthor()->getUsername(true), '/profile/my-tasks');
                            } else {
                                echo Html::a($task->getAuthor()->getUsername(true), ['/profile/view', 'id' => $task->getAuthorId()]);
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td><code><?= $task->getCreatedDate() ?></code></td>
                </tr>
                <tr>
                    <td>Дата обновления</td>
                    <td><code><?= $task->getUpdatedDate() ?></code></td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td> <?= $task->getStatusAsText() ?> </td>
                </tr>

                <?php if(Yii::$app->user->isManager($task->getProjectId())): ?>
                    <tr>
                        <td>Область видимости</td>
                        <td> <?= $task->getVisibilityAreaAsText() ?> </td>
                    </tr>
                <?php endif; ?>

                <?php if($task->getStatus() === TaskEntity::STATUS_ON_CONSIDERATION || $task->getStatus() === TaskEntity::STATUS_IN_PROGRESS): ?>

                    <tr>
                        <td>Будет завершена</td>
                        <td><code><?= $task->getPlannedEndDate() ?></code></td>
                    </tr>

                <?php elseif ($task->getStatus() === TaskEntity::STATUS_COMPLETED): ?>

                    <tr>
                        <td>Дата завершения</td>
                        <td><code><?= $task->getEndDate() ?></code></td>
                    </tr>

                <?php elseif ($task->getStatus() === TaskEntity::STATUS_MERGED): ?>

                    <tr>
                        <td>Родительская задача</td>
                        <td> <?= Html::a($task->getParent()->getTitle(true), ['task/view', 'id' => $task->getParent()->getId()]) ?> </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

            <?php
            if(Yii::$app->user->isManager($task->getProjectId()) ||
                Yii::$app->user->getId() === $task->getAuthorId())
            {
                echo Html::a('Редактировать', ['/task/edit', 'id' => $task->getId()], ['class' => 'btn btn-success edit-task-btn']);
            }
            ?>

        </div>
    </div>

    <?php if(!Yii::$app->user->isGuest): ?>

    <div class="row">

        <div class="col-md-8 ">

            <h4><a name="write-comment">Оставить комментарий</a></h4>

            <br>

            <?php
            $form = ActiveForm::begin([
                    'id' => 'comment-form'
            ]);
            ?>

                <?= $form->field($model, 'content')->textarea(['rows' => 2]) ?>

                <?= $form->field($model, 'parentId')->hiddenInput(['id' => 'comment-form-parent-id'])->label(false) ?>

                <div class="comment-form-parent-information"><p>Ответ на: <span class="comment-parent-number"></span></p>
                    <i class="glyphicon glyphicon-remove comment-form-delete-parent"></i>
                </div>

                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>

    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="comments">

                <h4 class="comment-amount">Комментарии (<?= $dataProvider->getTotalCount() ?>)</h4>

                <?php
                /**
                 * @var CommentEntity $comment
                 */
                foreach ($comments as $comment):
                ?>
                    <div class="comment-block <?php if($comment->isOwn()) { echo 'own-comment'; } ?>">

                        <div class="comment"
                             data-comment-id="<?= $comment->getId() ?>"
                             data-current-user-liked-it="<?= $comment->getCurrentUserLikedIt() ?>"
                             data-current-user-disliked-it="<?= $comment->getCurrentUserDislikedIt() ?>">

                            <div class="media-left">
                                <?php
                                if(!$comment->getDeleted()) {
                                    echo Html::img($comment->getUser()->getAvatarAlias(), ['class' => 'comment-avatar']);
                                }
                                ?>
                            </div>
                            <div class="media-right">
                                <div class="comment-title">
                                    <h5 class="comment-fio no-margin-top">
                                        <?php
                                        if (!$comment->getDeleted()) {
                                            if (Yii::$app->user->isGuest) {
                                                echo Html::a($comment->getUser()->getUsername(true), ['/site/login'], ['name' => $comment->getId()]);
                                            } else {
                                                if ($comment->getSenderId() === Yii::$app->user->getId()) {
                                                    echo Html::a($comment->getUser()->getUsername(true), ['/profile/my-projects'], ['name' => $comment->getId()]);
                                                } else {
                                                    echo Html::a($comment->getUser()->getUsername(true), ['/profile/view', 'id' => $comment->getUser()->getId()], ['name' => $comment->getId()]);
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                        if ($comment->getPrivate() && !$comment->getDeleted()) {
                                            echo '(Приватный комментарий)';
                                        }
                                        ?>
                                    </h5>
                                    <p class="comment-number">
                                        <?php echo '#' . ($counter + $increment); $counter++; ?>
                                        <?php if($isManager): ?>
                                            <?php if(!$comment->getDeleted()): ?>
                                                <i class="comment-edit-icon glyphicon glyphicon-pencil" title="Редактировать"></i>
                                                <i class="comment-delete-icon glyphicon glyphicon-trash" title="Удалить" data-comment-id="<?= $comment->getId() ?>"></i>
                                                <?php if($comment->getPrivate()): ?>
                                                    <i class="comment-make-public-icon glyphicon glyphicon-eye-open" title="Сделать публичным" data-comment-id="<?= $comment->getId() ?>"></i>
                                                <?php else: ?>
                                                    <i class="comment-make-private-icon glyphicon glyphicon-eye-close" title="Сделать приватным" data-comment-id="<?= $comment->getId() ?>"></i>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <i class="comment-reestablish glyphicon glyphicon-share" title="Восстановить" data-comment-id="<?= $comment->getId() ?>"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <?php if(!$comment->getDeleted()): ?>

                                    <?php if($comment->getParentId() !== null): ?>

                                        <?php $parent = $comment->getParent(); ?>
                                        <div class="comment-parent">
                                            <?php if(!$parent->getDeleted() && !$parent->getPrivate()): ?>
                                                <div class="comment-parent-username">
                                                    <?= Html::a($parent->getUser()->getUsername(true), ['/profile/view', 'id' => $parent->getUser()->getId()]) ?>
                                                </div>
                                                <div class="comment-parent-content"> <?= $parent->getContent(true) ?> </div>
                                            <?php elseif ($parent->getDeleted()): ?>
                                                Комментарий удален
                                            <?php elseif ($parent->getPrivate()): ?>
                                                Комментарий был помечен как приватный
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="comment-content" data-comment-id="<?= $comment->getId() ?>">
                                        <?= $comment->getContent(true); ?>
                                    </div>

                                    <?php if($isManager): ?>
                                    <div class="btn-edit-group">
                                        <div class="change-comment btn btn-primary btn-sm">Изменить</div>
                                        <div class="cancel-edit-comment btn btn-primary btn-sm">Отмена</div>
                                    </div>
                                    <?php endif; ?>

                                <?php else: ?>

                                    <div class="deleted-comment">Комментарий удален</div>

                                <?php endif; ?>

                                <?php if(!$comment->getDeleted()): ?>
                                    <div class="footer-comment">
                                        <div class="footer-comment-left">
                                            <span class="comment-date"><?= $comment->getCreatedAtDate() ?></span>
                                            <?php if (!Yii::$app->user->isGuest): ?>
                                                <?php if (!$comment->getPrivate()): ?>
                                                    <a href="#write-comment" class="comment-reply">Ответить</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="footer-comment-right">
                                        <span class="vote-up" title="Нравится">
                                            <i class="glyphicon glyphicon-thumbs-up" data-user-guest="<?= Yii::$app->user->isGuest ?>"><?= $comment->getLikesAmount() ?></i>
                                        </span>
                                            <span class="vote-down" title="Нравится">
                                            <i class="glyphicon glyphicon-thumbs-down" data-user-guest="<?= Yii::$app->user->isGuest ?>"><?= $comment->getDislikesAmount() ?></i>
                                        </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    endforeach;
                    ?>
                <?php
                // display pagination
                echo LinkPager::widget([
                    'pagination' => $dataProvider->getPagination(),
                ]);
                ?>
            </div>
        </div>
    </div>



