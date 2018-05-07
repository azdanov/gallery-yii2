<?php

declare(strict_types=1);

/* @var $this yii\web\View */

/* @var $postForm app\modules\post\models\forms\PostForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="post-default-index">

    <h1>Create post</h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($postForm, 'picture')->fileInput(); ?>

    <?= $form->field($postForm, 'description'); ?>

    <?= Html::submitButton('Create'); ?>

    <?php ActiveForm::end(); ?>

</div>
