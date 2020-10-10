<?php

namespace app\components\yii;

use app\components\controllers\BaseApiController;
use app\components\events\ErrorHandlerEvent;
use app\components\exceptions\UnSuccessException;
use app\components\exceptions\UserException;
use app\components\JSendResponse;
use Yii;
use Exception;

class ErrorHandler extends \yii\web\ErrorHandler
{
    public const EVENT_AFTER_API_ERROR_HANDLER = 'afterApiErrorHandler';

    public function isApiRequest(): bool
    {
        return Yii::$app->controller instanceof BaseApiController;
    }

    /**
     * @param Exception $exception
     * @return array
     */
    protected function convertExceptionToArray($exception): array
    {
        if ($this->isApiRequest()) {
            $apiErrorHandlerEvent = new ErrorHandlerEvent(['sender' => $this]);

            if ($exception instanceof UnSuccessException) {
                $array = [
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'errors' => $exception->getErrors(),
                ];
            } else {
                $jSendResponse = JSendResponse::error($exception->getMessage());
                $array = $jSendResponse->toArray();
            }

            $apiErrorHandlerEvent->result = $array;

            if (YII_DEBUG) {
                $array['debug']['type'] = get_class($exception);
                if (!$exception instanceof UserException) {
                    $array['debug']['file'] = $exception->getFile();
                    $array['debug']['line'] = $exception->getLine();
                    $array['debug']['stack-trace'] = explode("\n", $exception->getTraceAsString());
                    if ($exception instanceof \yii\db\Exception) {
                        $array['debug']['error-info'] = $exception->errorInfo;
                    }
                }

                $apiErrorHandlerEvent->debug = $array['debug'];
            }

            Yii::$app->trigger(static::EVENT_AFTER_API_ERROR_HANDLER, $apiErrorHandlerEvent);
        } else {
            $array = parent::convertExceptionToArray($exception);
        }

        return $array;
    }
}
