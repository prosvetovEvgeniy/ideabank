<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\repositories\message\MessageRepository;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Idea Bank',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Зарегистрироваться', 'items' => [
                            ['label' => 'Как пользователь', 'url' => ['/site/signup-user']],
                            ['label' => 'Как руководитель', 'url' => ['/site/signup-director']],
        ]];
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {


        $menuItems[] = ['label' => 'Уведомления', 'url' => '/notice/index'];

        $menuItems[] = ['label' => 'Проекты', 'url' => '/project/index'];

        $unViewedMessagesCount = MessageRepository::instance()->getAllUnViewedMsgCount();

        if($unViewedMessagesCount !== 0) {
            $unViewedMessagesCount = ' <code>+(' . $unViewedMessagesCount . ') </code>' ;
        } else {
            $unViewedMessagesCount = null;
        }

        $menuItems[] = ['label' => 'Аккаунт (' . Yii::$app->user->identity->getUserName(true) . ')', 'items' => [
            ['label' => 'Личный кабинет', 'url' => ['/profile/change-own-data']],
            ['label' => 'Сообщения' . $unViewedMessagesCount, 'url' => ['/message/dialog']],
            ['label' => 'Создать задачу', 'url' => ['/task/create']],
            //['label' => 'Помощь', 'url' => ['/help/index']],
            ['label' => 'Выход', 'url' => ['/site/logout']],
        ]];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <br>
        <br>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ideabank <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
