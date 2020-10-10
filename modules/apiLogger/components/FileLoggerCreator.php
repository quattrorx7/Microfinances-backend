<?php

namespace app\modules\apiLogger\components;

use app\modules\apiLogger\services\FileLoggerService;

class FileLoggerCreator implements LoggerFactoryInterface
{
    public function createService(): FileLoggerService
    {
        return new FileLoggerService();
    }
}