<?php

declare(strict_types=1);

use app\models\User;
use yii\authclient\clients\GitHub;
use yii\authclient\Collection;
use yii\caching\FileCache;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use yii\web\UrlNormalizer;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

(new Dotenv\Dotenv(\dirname(__DIR__)))->load();

$config = [
    'id' => 'yii2gallery',
    'name' => $params['siteName'],
    'basePath' => \dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => app\modules\user\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => \getenv('COOKIE_VALIDATION'),
        ],
        'cache' => [
            'class' => FileCache::class,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'normalizer' => [
                'class' => UrlNormalizer::class,
                // use temporary redirection instead of permanent for debugging
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                '' => 'site/index',
                '<controller:(user)>/<module:(profile)>/<action:\w+>/<id:\d+>' => '<controller>/<module>/<action>',
                '<controller:(user)>/<action:[\w-]+>/' => '<controller>/default/<action>',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
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
        'allowedIPs' => ['127.0.0.1', '::1', '172.19.0.1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '172.19.0.1'],
    ];
}

return $config;
