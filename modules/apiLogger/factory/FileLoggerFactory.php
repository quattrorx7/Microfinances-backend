<?php

namespace app\modules\apiLogger\factory;

use app\modules\apiLogger\models\FileLoggerModel;
use Exception;
use yii\base\BaseObject;
use yii\web\HeaderCollection;

class FileLoggerFactory extends BaseObject
{
    /**
     * @param string $method
     * @param string $url
     * @param array $bodyParams
     * @param HeaderCollection $headers
     * @return FileLoggerModel
     * @throws Exception
     */
    public function createFromRequestData(string $method, string $url, array $bodyParams, HeaderCollection $headers): FileLoggerModel
    {
        return FileLoggerModel::loadFromRequestBodyParams($method, $url, $bodyParams, $headers);
    }

    public function createFromLogLine(string $line): FileLoggerModel
    {
        return FileLoggerModel::loadFromLogLine($line);
    }
}