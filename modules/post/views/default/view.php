<?php

declare(strict_types=1);

/* @var $this \yii\web\View */
/* @var $post \app\models\Post */

/* @var $currentUser \app\models\User */

use yii\helpers\Html;
use yii\web\JqueryAsset;

$this->registerJsFile(
    '@web/js/likes.js',
    [
        'depends' => JqueryAsset::class,
    ]
);

$isLikedByCurrentUser = $currentUser && $post->isLikedBy($currentUser);

?>
<div class="post-default-index">

    <div class="row">

        <div class="col-md-12">
            <?php if ($post->user) : ?>
                <?= $post->user->username; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <img src="<?= $post->getImage(); ?>" />
        </div>

        <div class="col-md-12">
            <?= Html::encode($post->description); ?>
        </div>

    </div>

    <hr>

    <div class="col-md-12">
        <span>Likes: <span class="likes-count"><?= $post->countLikes(); ?></span></span>

        <button class="btn btn-primary btn-unlike <?= $isLikedByCurrentUser ? '' : 'hidden'; ?>"
           data-id="<?= $post->id; ?>">
            Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
        </button>
        <button class="btn btn-primary btn-like <?= $isLikedByCurrentUser ? 'hidden' : ''; ?>"
           data-id="<?= $post->id; ?>">
            Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
        </button>

    </div>

</div>
