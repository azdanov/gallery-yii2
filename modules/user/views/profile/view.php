<?php

declare(strict_types=1);

/* @var $user app\models\User */

use yii\helpers\Html;

$title = $user->username;
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>

<h1>Hello <?= Html::encode($user->username); ?></h1>
