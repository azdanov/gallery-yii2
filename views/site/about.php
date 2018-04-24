<?php

declare(strict_types=1);

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?php echo Html::encode($this->title); ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <code><?php echo __FILE__; ?></code>
</div>
