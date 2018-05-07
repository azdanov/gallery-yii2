<?php

declare(strict_types=1);

use app\components\Storage;
use app\models\User;
use yii\authclient\clients\GitHub;
use yii\authclient\Collection;
use yii\log\FileTarget;
use yii\redis\Cache;
use yii\redis\Connection;
use yii\redis\Session;
use yii\swiftmailer\Mailer;
use yii\web\UrlNormalizer;

(new Dotenv\Dotenv(\dirname(__DIR__)))->load();

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
    'id' => 'yii2gallery',
    'name' => $params['siteName'],
    'basePath' => \dirname(__DIR__),
    'bootstrap' => ['log'],
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => app\modules\user\Module::class,
        ],
        'post' => [
            'class' => app\modules\post\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => \getenv('COOKIE_VALIDATION'),
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mailtrap.io',
                'username' => 'eee02ae7e6ffec',
                'password' => '282d7afbd3f2bf',
                'port' => '2525',
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'cache' => [
            'class' => Cache::class,
        ],
        'session' => [
            'class' => Session::class,
        ],
        'redis' => [
            'class' => Connection::class,
            'hostname' => \getenv('CACHE_HOSTNAME'),
            'port' => \getenv('CACHE_PORT'),
            'database' => 0,
        ],
        'storage' => [
            'class' => Storage::class,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => UrlNormalizer::class,
                // use temporary redirection instead of permanent for debugging
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                '' => 'site/index',
                'profile/<identifier:\w+>' => 'user/profile/view',
                'post/<id:\d+>' => 'post/default/view',
            ],
        ],
        'authClientCollection' => [
            'class' => Collection::class,
            'clients' => [
                'github' => [
                    'class' => GitHub::class,
                    'clientId' => \getenv('CLIENT_ID'),
                    'clientSecret' => \getenv('CLIENT_SECRET'),
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.1'],
    ];
}

return $config;
