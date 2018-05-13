<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

/* @var $this \yii\web\View */
/* @var $post \app\models\Post */

/* @var $currentUser \app\models\User */

use yii\web\JqueryAsset;

$this->registerJsFile(
    '@web/js/likes.js',
    [
        'depends' => JqueryAsset::class,
    ]
);

$isLikedByCurrentUser = $currentUser && $post->isLikedBy($currentUser);
$likesPost = $currentUser->likesPost($post->id);
?>

<div class="container full">
    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">
                <div class="blog-posts blog-posts-large">
                    <div class="row">
                        <article class="post col-sm-12 col-xs-12">
                            <div class="post-meta">
                                <div class="post-title">
                                    <img src="<?= $post->user->getPicture(); ?>"
                                         class="author-image" />
                                    <div class="author-name">
                                        <a href="#"><?= $post->user->username; ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="post-type-image">
                                <a href="#">
                                    <img src="<?= $post->getImage(); ?>" />
                                </a>
                            </div>
                            <div class="post-description">
                                <p>
                                    <?= $post->description; ?>
                                </p>
                            </div>
                            <div class="post-bottom">
                                <div class="post-likes">
                                    <i class="far fa-heart"></i>
                                    <span title="Number of likes"
                                          class="likes-count"><?= $post->countLikes(); ?></span>
                                    <button title="Dislike Post"
                                            class="btn btn-default button-unlike <?= $likesPost ? '' : 'hidden'; ?>"
                                            data-id="<?= $post->id; ?>">
                                        <span class="far fa-thumbs-down"></span>
                                    </button>
                                    <button title="Like Post"
                                            class="btn btn-default button-like <?= $likesPost ? 'hidden' : ''; ?>"
                                            data-id="<?= $post->id; ?>">
                                        <span class="far fa-thumbs-up"></span>
                                    </button>
                                </div>
                                <div class="post-date">
                                    <span><?= Yii::$app->formatter->asDate($post->created_at); ?></span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
