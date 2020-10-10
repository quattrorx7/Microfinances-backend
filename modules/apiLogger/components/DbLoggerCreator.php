<?php

namespace app\modules\apiLogger\components;

use app\modules\apiLogger\services\DbLoggerService;

class DbLoggerCreator implements LoggerFactoryInterface
{
    public function createService(): DbLoggerService
    {
        return new DbLoggerService();
    }
}