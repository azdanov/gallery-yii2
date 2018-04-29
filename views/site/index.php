<?php

declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $users app\models\User[]|array */

use yii\helpers\Url;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p>
            <a class="btn btn-lg btn-success" href="http://www.yiiframework.com">
                Get started with Yii
            </a>
        </p>
    </div>

    <div class="body-content">

        <?php foreach ($users as $user) : ?>
            <a href="<?= Url::to(['/user/profile/view/', 'identifier' => $user->getNickname()]); ?>">
                <?= $user->username; ?>
            </a>
            <hr>
        <?php endforeach; ?>

    </div>
</div>
