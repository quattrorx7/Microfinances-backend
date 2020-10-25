<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$modules  = require __DIR__ . '/modules.php';
$urlRules = require __DIR__ . '/url-rules.php';
$mailer   = require __DIR__ . '/mailer.php';

if (file_exists(__DIR__ . '/local/db-local.php')) {
    $db = require __DIR__ . '/local/db-local.php';
}
if (file_exists(__DIR__ . '/local/params-local.php')) {
    $params = require __DIR__ . '/local/params-local.php';
}
if (file_exists(__DIR__ . '/local/mailer-local.php')) {
    $mailer = require __DIR__ . '/local/mailer-local.php';
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'name' => '',
    'bootstrap' => [
        'log',
        'app\bootstrap\ContainerBootstrap',
        'apiloggerQueue',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => $modules,
    'components' => [
        'apiloggerQueue'        => require __DIR__ . '/queues/apilogger-queue.php',
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'km3tio54ig4rgjnkrg43pfj3nfhb4312omfwjn',
        ],
        'fs' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path'  => '@webroot/files',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\models\UserConfig'
        ],
        'errorHandler' => [
            'class'       => \app\components\yii\ErrorHandler::class,
            'errorAction' => 'site/error',
        ],
        'mailer' => $mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $urlRules
        ],
        'i18n' => [
            'translations'=>[
                '*'=>[
                    'class'=>yii\i18n\PhpMessageSource::class,
                    'basePath'=>'@app/messages',
                    'sourceLanguage'=>'en-US',
                    'fileMap'=>[
                        'app'=>'app.php'
                    ]
                ]
            ]
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'adminlte' => '@vendor/dmstr/yii2-adminlte-asset/gii/templates/crud/simple',
                ]
            ]
        ],
    ];
}

return $config;
