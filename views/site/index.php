<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

/* @var $this yii\web\View */
/* @var $currentUser app\models\User */
/* @var $storage StorageInterface */
/* @var $feedItems app\models\Feed[] */

use app\components\StorageInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = Yii::$app->params['siteName'];

$this->registerJsFile(
    '@web/js/likes.js',
    [
        'depends' => JqueryAsset::class,
    ]
);

?>
<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12">
            <div class="blog-posts blog-posts-large">
                <div class="row">
                    <?php if ($feedItems) : ?>
                        <?php foreach ($feedItems as $feedItem) : ?>
                            <?php $storage = Yii::$app->storage; ?>
                            <?php $likesPost = $currentUser->likesPost($feedItem->post_id); ?>
                            <article class="post col-sm-12 col-xs-12">
                                <div class="post-meta">
                                    <div class="post-title">
                                        <img src="<?= $feedItem->author_picture; ?>"
                                             class="author-image" />
                                        <div class="author-name">
                                            <a href="<?= Url::to(['/user/profile/view', 'identifier' => $feedItem->author_nickname ?: $feedItem->author_id]); ?>">
                                                <?= Html::encode($feedItem->author_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-type-image">
                                    <a href="<?= Url::to(['/post/default/view', 'id' => $feedItem->post_id]); ?>">
                                        <img src="<?= $storage->getFile($feedItem->post_filename); ?>" alt="" />
                                    </a>
                                </div>
                                <div class="post-description">
                                    <p><?= Html::encode($feedItem->post_description); ?></p>
                                </div>
                                <div class="post-bottom">
                                    <div class="post-likes">
                                        <i class="far fa-heart"></i>
                                        <span title="Number of likes" class="likes-count"><?= $feedItem->countLikes(); ?></span>
                                        <button title="Dislike Post"
                                            class="btn btn-default button-unlike <?= $likesPost ? '' : 'hidden'; ?>"
                                           data-id="<?= $feedItem->post_id; ?>">
                                            <span class="far fa-thumbs-down"></span>
                                        </button>
                                        <button title="Like Post"
                                            class="btn btn-default button-like <?= $likesPost ? 'hidden' : ''; ?>"
                                           data-id="<?= $feedItem->post_id; ?>">
                                            <span class="far fa-thumbs-up"></span>
                                        </button>
                                    </div>
                                    <div class="post-comments">
                                        <a href="#">0 Comments</a>
                                    </div>
                                    <div class="post-date">
                                        <span><?= Yii::$app->formatter->asRelativeTime($feedItem->post_created_at); ?></span>
                                    </div>
                                    <div class="post-report">
                                        <a href="#">Report post</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-md-12">
                            Nobody posted yet!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


