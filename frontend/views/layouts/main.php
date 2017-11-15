<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
        'brandLabel' => Yii::$app->name,
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

        $participants = \common\models\Participant::find()->where(['user_id' => Yii::$app->user->identity->profile->id])
                                                         ->andWhere(['is not', 'company_id', null])->all();
        $companyes = [];

        foreach ($participants as $participant)
        {
            $companyes[] = ['label' => $participant->company->name, 'url' => ['/site/index', 'companyName' => $participant->company->name], 'active' => false];
        }

        $menuItems[] = ['label' => 'Мои компании', 'items' => $companyes];

        $menuItems[] = ['label' => 'Аккаунт (' . Yii::$app->user->identity->profile->username . ')', 'items' => [
            ['label' => 'Профиль', 'url' => ['/account/index']],

            ['label' => 'Выход', 'url' => ['/site/logout']],
        ]];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
