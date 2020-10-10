<?php

namespace app\modules\api;

use app\components\events\ErrorHandlerEvent;
use app\components\yii\ErrorHandler;
use app\modules\apiLogger\components\DbLoggerCreator;
use app\modules\apiLogger\components\FileLoggerCreator;
use yii\base\Application;
use Yii;

/**
 * api module definition class
 */
class ApiModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initEvents();
    }

    protected function initEvents(): void
    {
        $serviceFactory = new FileLoggerCreator();
        $apiLoggerService = $serviceFactory->createService();

        $this->on(Application::EVENT_BEFORE_ACTION, static function () use ($apiLoggerService) {
            $apiLoggerService->exportRequest(Yii::$app->request);
        });

        $this->on(Application::EVENT_AFTER_ACTION, static function ($event) use ($apiLoggerService) {
            $apiLoggerService->exportResponse(
                $event->result,
                Yii::$app->user->id
            );
        });

        Yii::$app->on(ErrorHandler::EVENT_AFTER_API_ERROR_HANDLER, static function (ErrorHandlerEvent $event) use ($apiLoggerService) {
            $apiLoggerService->exportResponse(
                $event->result,
                Yii::$app->user->id
            );
        });
    }

}
