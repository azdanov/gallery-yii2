<?php

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

/* @var $this yii\web\View */
/* @var $currentUser app\models\User */

/* @var $feedItems app\models\Feed[] */

use app\components\StorageInterface;
use app\models\Feed;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = 'My Yii Application';

$this->registerJsFile(
    '@web/js/likes.js',
    [
        'depends' => JqueryAsset::class,
    ]
);

?>
<div class="site-index">

    <?php if ($feedItems) : ?>
        <?php foreach ($feedItems as $feedItem) : ?>
            <?php /* @var $feedItem Feed */ ?>
            <?php /* @var $storage StorageInterface */ ?>

            <?php $userLikesPost = $currentUser->likesPost($feedItem->post_id); ?>
            <?php $storage = Yii::$app->storage; ?>

            <div class="col-md-12">

                <div class="col-md-12">
                    <img src="<?= $feedItem->author_picture; ?>" width="30" height="30" />
                    <a href="<?= Url::to(['/user/profile/view', 'identifier' => $feedItem->author_nickname ?: $feedItem->author_id]); ?>">
                        <?= Html::encode($feedItem->author_name); ?>
                    </a>
                </div>

                <img src="<?= $storage->getFile($feedItem->post_filename); ?>" />
                <div class="col-md-12">
                    <?= HtmlPurifier::process($feedItem->post_description); ?>
                </div>

                <div class="col-md-12">
                    <?= Yii::$app->formatter->asDatetime($feedItem->post_created_at); ?>
                </div>

                <div class="col-md-12">
                        <span>
                            Likes:
                            <span class="likes-count"><?= $feedItem->countLikes(); ?></span>
                        </span>

                    <button
                        class="btn btn-primary btn-unlike <?= $userLikesPost ? '' : 'hidden'; ?>"
                        data-id="<?= $feedItem->post_id; ?>">
                        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                    </button>
                    <button
                        class="btn btn-primary btn-like <?= $userLikesPost ? 'hidden' : ''; ?>"
                        data-id="<?= $feedItem->post_id; ?>">
                        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                    </button>
                </div>

            </div>
            <div class="col-md-12">
                <hr />
            </div>
        <?php endforeach; ?>

    <?php else : ?>
        <div class="col-md-12">
            Nobody posted yet!
        </div>
    <?php endif; ?>

</div>
