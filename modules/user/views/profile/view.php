<?php

declare(strict_types=1);

/* @var $user app\models\User */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$title = $user->username;
$this->title = $title.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>

<h1>Hello <?= Html::encode($user->username); ?></h1>
<p><?= HtmlPurifier::process($user->about); ?></p>
<hr>
