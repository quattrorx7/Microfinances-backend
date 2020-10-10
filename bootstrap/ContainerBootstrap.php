<?php

namespace app\bootstrap;

use app\components\constants\ModulesConst;
use app\dispatchers\DummyEventDispatcher;
use app\dispatchers\EventDispatcher;
use yii\base\BootstrapInterface;
use Yii;
use yii\web\Request;
use yii\web\Session;

class ContainerBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->setSingleton(EventDispatcher::class, DummyEventDispatcher::class);

        $request = Yii::$app->request;

        $this->configureApiModule($request);
    }


    private function configureApiModule(Request $request): void
    {
        $route = $request->pathInfo;
        $module = substr($route,0, strpos($route,'/'));

        if (Yii::$app->hasModule($module) && ($module === ModulesConst::API_MODULE_CONST)) {
            Yii::$app->user->enableSession = false;
            Yii::$app->user->enableAutoLogin = false;

            \Yii::$app->setComponents([
                'session' => [
                    'class' => Session::class,
                    'useCookies' => false
                ],
                'request' => [
                    'class' => 'yii\web\Request',
                    'parsers' => [
                        'application/json' => \yii\web\JsonParser::class,
                    ],
                    'cookieValidationKey' => Yii::$app->request->cookieValidationKey,
                    'enableCsrfValidation' => false,
                    'enableCsrfCookie' => false
                ]
            ]);

        }
    }

}