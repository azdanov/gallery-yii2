<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/* @var $user User */
/* @var $currentUser User */

/* @var $pictureForm PictureForm */

use app\models\User;
use app\modules\user\models\forms\PictureForm;
use dosamigos\fileupload\FileUpload;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

$title = $user->username;
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;

$isUser = !Yii::$app->user->isGuest;
?>

<h1><?= Html::encode($user->username); ?>'s Profile</h1>
<p><?= HtmlPurifier::process($user->about); ?></p>

<hr>

<img src="<?= $user->getPicture(); ?>" alt="<?= $user->username; ?>" id="profile-picture"
     class="author-image">

<?php if ($currentUser) : ?>
    <?php if ($currentUser->equals($user)) : ?>
        <?= FileUpload::widget(
            [
                'model' => $pictureForm,
                'attribute' => 'picture',
                'url' => ['/user/profile/upload-picture'],
                'options' => ['accept' => 'image/*'],
                'clientEvents' => [
                    'fileuploaddone' => '
                        function onUploadDone(e, data) {
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
                        }
                    ',
                ],
            ]
        ); ?>

        <a href="#" class="btn btn-default">Edit profile</a>

        <?= Html::beginForm(['/user/profile/delete-picture']).Html::submitButton(
            'Delete Picture',
            ['class' => 'btn btn-danger logout']
        ).Html::endForm(); ?>

        <div class="alert alert-success hidden" id="upload-image-success">Profile image updated
        </div>
        <div class="alert alert-danger hidden" id="upload-image-fail"></div>
    <?php endif; ?>

    <?php if (!$currentUser->equals($user)) : ?>
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
    <?php endif; ?>

    <?php $mutualSubscriptions = $currentUser->mutualSubscriptions($user); ?>
    <?php if ($mutualSubscriptions) : ?>
        <p>Friends, who are also following <?= Html::encode($user->username); ?>: </p>
        <div class="row">
            <?php foreach ($mutualSubscriptions as $subscription) : ?>
                <div class="col-md-12">
                    <a href="<?= Url::to(['/user/profile/view', 'identifier' => $subscription['nickname'] ?: $subscription['id']]); ?>">
                        <?= Html::encode($subscription['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endif; ?>
<?php endif; ?>

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
