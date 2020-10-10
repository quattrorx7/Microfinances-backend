<?php

namespace app\modules\apiLogger\components;

use app\modules\apiLogger\services\ApiLoggerServiceInterface;

interface LoggerFactoryInterface
{
    public function createService(): ApiLoggerServiceInterface;
}