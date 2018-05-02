<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/* @var $this \yii\web\View */
/* @var User $currentUser */
/* @var $content string */

use app\assets\AppAsset;
use app\models\User;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
$currentUser = Yii::$app->user->identity;
?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language; ?>">
    <head>
        <meta charset="<?= Yii::$app->charset; ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags(); ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>

    <div class="wrap">
        <?php
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
        ];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/user/default/signup']];
            $menuItems[] = ['label' => 'Login', 'url' => ['/user/default/login']];
        } else {
            $menuItems[] = ['label' => 'Profile', 'url' => [Url::to(['/user/profile/view', 'identifier' => $currentUser->id])]];
            $menuItems[] = '<li>'.Html::beginForm(['/user/default/logout']).Html::submitButton(
                'Logout ('.$currentUser->username.')',
                ['class' => 'btn btn-link logout']
            ).Html::endForm().'</li>';
        }

        NavBar::begin(
            [
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]
        );
        echo Nav::widget(
            [
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]
        );
        NavBar::end();
        ?>

        <main class="container">
            <?= Breadcrumbs::widget(
                [
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]
            ); ?>
            <?= Alert::widget(); ?>
            <?= $content; ?>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Yii2 Gallery <?= \date('Y'); ?></p>

            <p class="pull-right"><?= Yii::powered(); ?></p>
        </div>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage();
