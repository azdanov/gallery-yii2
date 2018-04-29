<?php

declare(strict_types=1);

/* @var $user app\models\User */

/* @var $currentUser User */

use app\models\User;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

$title = $user->username;
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>

<h1>Hello <?= Html::encode($user->username); ?></h1>
<p><?= HtmlPurifier::process($user->about); ?></p>
<hr>

<a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>"
   class="btn btn-info">Subscribe
</a>
<a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>"
   class="btn btn-info">Unsubscribe
</a>

<hr>

<p>Friends, who are also following <?= Html::encode($user->username); ?>: </p>
<div class="row">
    <?php foreach ($currentUser->mutualSubscriptions($user) as $item) : ?>
        <div class="col-md-12">
            <a href="<?= Url::to(['/user/profile/view', 'identifier' => $item['nickname'] ?: $item['id']]); ?>">
                <?= Html::encode($item['username']); ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<hr>

<button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
        data-target="#subscriptionModal">
    Subscriptions: <?= $user->countSubscriptions(); ?>
</button>
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
        data-target="#followersModal">
    Followers: <?= $user->countFollowers(); ?>
</button>
<div>
    <?= $this->render(
        '_modal',
        [
            'label' => 'Subscriptions',
            'users' => $user->getSubscriptions(),
            'id' => 'subscriptionModal',
        ]
    ); ?>
    <?= $this->render(
        '_modal',
        [
            'label' => 'Followers',
            'users' => $user->getFollowers(),
            'id' => 'followersModal',
        ]
    ); ?>
</div>

