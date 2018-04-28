<?php

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(
    ['/user/default/reset-password', 'token' => $user->password_reset_token]
);
?>
Hello <?php echo $user->username; ?>,

Follow the link below to reset your password:

<?php echo $resetLink;
