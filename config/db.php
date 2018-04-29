<?php

declare(strict_types=1);

use yii\db\Connection;

$dbHost = \getenv('DB_HOST');
$dbName = \getenv('DB_NAME');
$dbUsername = \getenv('DB_USERNAME');
$dbPassword = \getenv('DB_PASSWORD');

return [
    'class' => Connection::class,
    'dsn' => "$dbHost;dbname=$dbName",
    'username' => $dbUsername,
    'password' => $dbPassword,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
