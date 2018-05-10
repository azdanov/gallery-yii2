<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/* @var $this \yii\web\View */

/* @var $currentUser User */

/* @var $content string */

use app\assets\AppAsset;
use app\assets\FontAwesomeAsset;
use app\assets\PicturefillAsset;
use app\models\User;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
FontAwesomeAsset::register($this);
PicturefillAsset::register($this);

$currentUser = Yii::$app->user->identity;
?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language; ?>">
    <head>
        <meta charset="<?= Yii::$app->charset; ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags(); ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <?php $this->beginBody(); ?>
    <body class="home page">

    <div class="wrapper">
        <header>
            <div class="header-top">
                <div class="container">
                    <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4 brand-logo">
                        <h1>
                            <a href="<?= Url::to(['/site/index']); ?>">
                                <picture>
                                    <source type="image/svg+xml" srcset="/img/logo.svg">
                                    <img src="/img/logo.png" alt="Logo">
                                </picture>
                            </a>
                        </h1>
                    </div>
                    <div class="col-md-4 col-sm-4 navicons-topbar">
                        <ul>
                            <li class="blog-search">
                                <a href="#" title="Search"><i class="fas fa-search"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="header-main-nav">
                <div class="container">
                    <div class="main-nav-wrapper">
                        <nav class="main-menu">

                            <?php
                            $menuItems = [
                                ['label' => 'Home', 'url' => ['/site/index']],
                            ];
                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = [
                                    'label' => 'Signup',
                                    'url' => ['/user/default/signup'],
                                ];
                                $menuItems[] = [
                                    'label' => 'Login',
                                    'url' => ['/user/default/login'],
                                ];
                            } else {
                                $menuItems[] = [
                                    'label' => 'My profile',
                                    'url' => [
                                        '/user/profile/view',
                                        'identifier' => $currentUser->getNickname(),
                                    ],
                                ];
                                $menuItems[] = [
                                    'label' => 'Create post',
                                    'url' => ['/post/default/create'],
                                ];
                                $menuItems[] = '<li>'.Html::beginForm(
                                    ['/user/default/logout']
                                ).Html::submitButton(
                                    'Logout ('.$currentUser->username.') <i class="fas fa-sign-out-alt"></i>',
                                    ['class' => 'btn btn-link logout']
                                ).Html::endForm().'</li>';
                            }
                            echo Nav::widget(
                                [
                                    'options' => ['class' => 'menu navbar-nav navbar-right'],
                                    'items' => $menuItems,
                                ]
                            );
                            ?>
                        </nav>
                    </div>
                </div>
            </div>

        </header>

        <div class="container full">
            <?= Alert::widget(); ?>
            <?= $content; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="back-to-top-page">
            <a class="back-to-top"><i class="fas fa-angle-double-up"></i></a>
        </div>
        <p class="text">Yii2 Gallery | <?= date('Y'); ?></p>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage();
