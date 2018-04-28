<?php

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(
    ['/user/default/reset-password', 'token' => $user->password_reset_token]
);
?>
<div class="password-reset">
    <p>Hello <?php echo Html::encode($user->username); ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?php echo Html::a(Html::encode($resetLink), $resetLink); ?></p>
</div>
