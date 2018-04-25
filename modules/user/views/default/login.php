<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \app\modules\user\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = 'Login';
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-7 col-lg-offset-1">
            <h1><?php echo $title; ?></h1>

            <p>Please fill out the following fields to login:</p>

            <div class="row">
                <div class="col-lg-10">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?php echo $form->field($model, 'email')->textInput(
                        ['autofocus' => true]
                    ); ?>

                    <?php echo $form->field($model, 'password')->passwordInput(); ?>

                    <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>

                    <div style="color:#999;margin:1em 0;">
                        If you forgot your password you can <?php echo Html::a(
                            'reset it',
                            ['/user/default/request-password-reset']
                        ); ?>.
                    </div>

                    <div class="form-group">
                        <?php echo Html::submitButton(
                            'Login',
                            ['class' => 'btn btn-primary', 'name' => 'login-button']
                        ); ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <h2>Instant Login</h2>
            <br>
            <?php echo yii\authclient\widgets\AuthChoice::widget(
                [
                    'baseAuthUrl' => ['/user/default/auth'],
                    'popupMode' => false,
                ]
            ); ?>
        </div>
    </div>
</div>
