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

    <?php echo $form->field($postForm, 'picture')->fileInput(); ?>

    <?php echo $form->field($postForm, 'description'); ?>

    <?php echo Html::submitButton('Create'); ?>

    <?php ActiveForm::end(); ?>

</div>
