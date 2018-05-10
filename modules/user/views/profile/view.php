<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $currentUser app\models\User */
/* @var $pictureForm app\modules\user\models\forms\PictureForm */
/* @var Post $post */

use app\components\StorageInterface;
use app\models\Post;
use dosamigos\fileupload\FileUpload;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $user->username;

/** @var StorageInterface $storage */
$storage = Yii::$app->storage;
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">
            <div class="blog-posts blog-posts-large">
                <div class="row">
                    <article class="profile col-sm-12 col-xs-12">
                        <div class="profile-title">
                            <img src="<?= $user->getPicture(); ?>" id="profile-picture"  class="author-image" />

                            <div class="author-name"><?= Html::encode($user->username); ?></div>

                            <?php if ($currentUser && $currentUser->equals($user)) : ?>
                                <?=
                                FileUpload::widget([
                                    'model' => $pictureForm,
                                    'attribute' => 'picture',
                                    'url' => ['/user/profile/upload-picture'],
                                    'options' => ['accept' => 'image/*'],
                                    'clientEvents' => [
                                        'fileuploaddone' => 'function onFileUploadDone(e, data) {
                                            if (data.result.success) {
                                                $("#upload-image-success").removeClass("hidden");
                                                $("#upload-image-fail").addClass("hidden");
                                                $("#profile-picture").attr("src", data.result.pictureUri);
                                            } else {
                                                $("#upload-image-fail")
                                                    .html(data.result.errors.picture)
                                                    .removeClass("hidden");
                                                $("#upload-image-success").addClass("hidden");
                                            }
                                        }',
                                    ],
                                ]);
                                ?>
                                <a href="#" class="btn btn-default">Edit profile</a>
                            <?php endif; ?>
                            <br/>
                            <br/>
                            <div class="alert alert-success hidden" id="upload-image-success">Profile image updated</div>
                            <div class="alert alert-danger hidden" id="upload-image-fail"></div>
                        </div>

                        <?php if ($currentUser && !$currentUser->equals($user)) : ?>
                            <?php if ($currentUser->isSubscribedTo($user)) : ?>
                                <a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>"
                                   class="btn btn-info">Unsubscribe
                                </a>
                            <?php else : ?>
                                <a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>"
                                   class="btn btn-info">Subscribe
                                </a>
                            <?php endif; ?>
                            <hr>
                            <h5>Friends, who are also following <?= Html::encode($user->username); ?>: </h5>
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
                        <?php endif; ?>

                        <?php if ($user->about) : ?>
                            <div class="profile-description">
                                <p><?= Html::encode($user->about); ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="profile-bottom">
                            <div class="profile-post-count">
                                <span><?= $user->getPostCount(); ?> posts</span>
                            </div>
                            <div class="profile-followers">
                                <a href="#" data-toggle="modal" data-target="#followersModal"><?= $user->countFollowers(); ?> followers</a>
                            </div>
                            <div class="profile-following">
                                <a href="#" data-toggle="modal" data-target="#followingModal"><?= $user->countSubscriptions(); ?> following</a>
                            </div>
                        </div>
                    </article>

                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <?php foreach ($user->getPosts() as $post) : ?>
                                <div class="col-md-4 profile-post">
                                    <a href="<?= Url::to(['/post/default/view', 'id' => $post->getId()]); ?>">
                                        <img src="<?= $storage->getFile($post->filename); ?>" class="author-image" />
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal subscriptions -->
<div class="modal fade" id="followingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $subscription) : ?>
                        <div class="col-md-12">
                            <a href="<?= Url::to(['/user/profile/view', 'identifier' => $subscription['nickname'] ?: $subscription['id']]); ?>">
                                <?= Html::encode($subscription['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal subscriptions -->

<!-- Modal followers -->
<div class="modal fade" id="followersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $follower) : ?>
                        <div class="col-md-12">
                            <a href="<?= Url::to(['/user/profile/view', 'identifier' => $follower['nickname'] ?: $follower['id']]); ?>">
                                <?= Html::encode($follower['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal followers -->
