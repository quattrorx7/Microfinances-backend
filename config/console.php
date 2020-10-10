<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$modules  = require __DIR__ . '/modules.php';
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
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'apiloggerQueue'
    ],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'modules' => $modules,
    'components' => [
        'apiloggerQueue'        => require __DIR__ . '/queues/apilogger-queue.php',
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => $mailer,
        'db' => $db,
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                'app\migrations',
                'app\modules\apiLogger\migrations',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model-base' => [
                'class'      => app\components\gii\generators\model\base\Generator::class,
                'ns'         => '\app\models\base',
                'baseClass'  => \app\components\yii\BaseAR::class,
                'modelsNs'   => '\app\models',
                'enableI18N' => true,
                'queryesNs'  => '\app\models\query',
            ],
            'model-work' => [
                'class'        => \app\components\gii\generators\model\work\Generator::class,
                'ns'           => 'app\models',
                'baseModelsNs' => '\app\models\base'
            ],
            'crud'       => [
                'class' => \app\components\gii\generators\crud\Generator::class,
            ],
            'mod'       => [
                'class' => \app\components\gii\generators\mod\Generator::class,
            ],
            'api'       => [
                'class' => \app\components\gii\generators\api\Generator::class
            ],
        ],
    ];
}

return $config;
