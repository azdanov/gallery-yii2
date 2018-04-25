<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(
    ['/user/default/reset-password', 'token' => $user->password_reset_token]
);
?>
Hello <?php echo $user->username; ?>,

Follow the link below to reset your password:

<?php echo $resetLink; ?>
