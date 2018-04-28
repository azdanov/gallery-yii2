<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \app\modules\user\models\PasswordResetRequestForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = 'Request password reset';
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>
<div class="site-request-password-reset">
    <h1><?= $title; ?></h1>

    <p>Please fill out your email. A link to reset password will be sent there.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

            <?= $form->field($model, 'email')
                ->textInput(['autofocus' => true, 'placeholder' => 'Email']); ?>

            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
