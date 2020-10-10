<?php

namespace app\modules\apiLogger\repository;

use app\modules\apiLogger\helpers\ApiLoggerHelper;
use app\modules\apiLogger\models\LoggerModelInterface;
use yii\base\Exception;

class FileLoggerRepository implements LoggerRepositoryInterface
{
    /**
     * @param LoggerModelInterface $fileLoggerModel
     * @throws Exception
     */
    public function save(LoggerModelInterface $fileLoggerModel): void
    {
        $filename = (new ApiLoggerHelper())->getLogFileName();

        $dateStarted = $fileLoggerModel->getDateStarted();
        $duration = round($fileLoggerModel->getDuration() / 1000, 2);
        $method = $fileLoggerModel->getMethod();
        $url = $fileLoggerModel->getUrl();
        $bodyParams = ApiLoggerHelper::transformStringForCompress($fileLoggerModel->getBodyParams());
        $headers = ApiLoggerHelper::transformStringForCompress($fileLoggerModel->getHeaders());
        $userId = $fileLoggerModel->getUserId();
        $appPlatform = $fileLoggerModel->getAppPlatform();
        $appVersion = $fileLoggerModel->getAppVersion();
        $response = ApiLoggerHelper::transformStringForCompress($fileLoggerModel->getResponse());

        $text = "$dateStarted]][[$duration]][[$userId]][[$appPlatform]][[$appVersion]][[$method]][[$url]][[$headers]][[$bodyParams]][[$response \n";

        $fp = fopen($filename, 'a');
        flock($fp, LOCK_EX);
        fwrite($fp, $text);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}