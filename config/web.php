<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => '1Brest.by',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/parts/index',
    'bootstrap' => ['log', 'app\components\SetUp', 'queue'],
    'language' => 'ru-RU',
    'modules' => [
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'components' => [
        'geo' => [
            'class' => 'app\components\geo\Geo',
        ],
        'currency' => [
            'class' => 'app\components\Currency',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm'
        ],
        'request' => [
            'cookieValidationKey' => 'xYNGiG5ZF00M5g55p8HvRvGKQEiOYSo7',
        ],
        'authManager' => [
            'class' => 'app\components\AuthManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/main/parts/index/', 'showLogin' => 1],
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'assetManager' => [
            'bundles' => false,
            'appendTimestamp' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'path' => '@runtime/queue',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'page-<page:[\d]+>' => 'main/parts/index',
                '' => 'main/parts/index',

                'admin/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:[\d]+>/<photo_id:[\d]+>' => 'admin/<_c>/<_a>',

                'services/auto/auto-services' => 'main/services/auto/auto-service/index',
                'services/auto/auto-services/<id:\d+>' => 'main/services/auto/auto-service/view',

                '<_a:(login|logout|signup|password-reset-request|password-reset|email-confirm)>' => 'user/default/<_a>',
                '<_c:(parts|cars|tires|wheels)>' => 'main/<_c>/index',
                '<_c:(cars|tires|wheels)>/ad<id:[\d]+>' => 'main/<_c>/view',
                '<_c:(parts|cars|tires|wheels)>/ad<id:[\d]+>/<_a:[\w\-]+>' => 'main/<_c>/<_a>',
                '<_c:(parts|cars|tires|wheels)>/<_a:[\w\-]+>' => 'main/<_c>/<_a>',

                '<_m:[\w\-]+>' => '<_m>/default/index',
                '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:[\d]+>' => '<_m>/<_c>/view',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:[\d]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        //'ide' => 'phpstorm',
        //'traceLine' => '<a href="http://autobrest.testiruem.xyz/api/file?file={file}&line={line}" onclick="var a=new XMLHttpRequest;a.open(\'GET\',this.href);a.send(null);return!1">{text}</a>',
        'allowedIPs' => ['*'],
        'panels' => [
            'queue' => \yii\queue\debug\Panel::class,
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'generators' => [ //here
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'adminlte' => '@app/modules/admin/gii-templates/crud/simple',
                ]
            ]
        ],
    ];
}

return $config;