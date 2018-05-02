<?php

declare(strict_types=1);

$db = require __DIR__.'/db.php';
$db['dsn'] = 'mysql:host=db;dbname=yii2_basic_tests';

return $db;
