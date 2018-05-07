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
                <?php echo $post->user->username; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>" />
        </div>

        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

    </div>

    <hr>

    <div class="col-md-12">
        <span>Likes: <span id="likesCount"><?php echo $post->countLikes(); ?></span></span>

        <a href="#"
           id="buttonUnlike"
           class="btn btn-primary <?php echo $isLikedByCurrentUser ? '' : 'hidden'; ?>"
           data-id="<?php echo $post->id; ?>">
            Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
        </a>
        <a href="#"
           id="buttonLike"
           class="btn btn-primary <?php echo $isLikedByCurrentUser ? 'hidden' : ''; ?>"
           data-id="<?php echo $post->id; ?>">
            Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
        </a>

    </div>

</div>
