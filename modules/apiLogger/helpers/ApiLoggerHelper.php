<?php

namespace app\modules\apiLogger\helpers;

use DateTime;
use DateTimeZone;
use Exception;
use yii\helpers\FileHelper;
use Yii;
use yii\helpers\Json;

class ApiLoggerHelper
{
    public int $dirMode = 0775;

    public string $timezone = 'Europe/Moscow';

    public string $logFile;

    public function __construct()
    {
        $filename = $this->getCurrentDate('Ymd') . '.log';
        $this->logFile = Yii::getAlias(Yii::$app->getModule('apiLogger')->params['filesDirectory']) . $filename;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getLogFileName(): string
    {
        $logPath = dirname($this->logFile);
        FileHelper::createDirectory($logPath, $this->dirMode, true);

        return $this->logFile;
    }

    /**
     * @param string $format
     * @return string
     * @throws Exception
     */
    public function getCurrentDate($format = 'Y-m-d H:i:s'): string
    {
        return (new DateTime('now', new DateTimeZone($this->timezone)))->format($format);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCurrentDateWithMicro(): string
    {
        $tt = DateTime::createFromFormat('U.u', microtime(TRUE));
        $tt->setTimeZone(new DateTimeZone($this->timezone));

        return $tt->format('Y-m-d H:i:s.u');
    }

    public static function transformStringForCompress($str)
    {
        $res = gzcompress(Json::encode($str));
        $res = str_replace(array("\r", "\n"), array('+++', '---'), $res);
        return $res;
    }

    public static function transformCompressToString($str)
    {
        $res = str_replace(array('+++', '---'), array("\r", "\n"), $str);
        $res = Json::decode(gzuncompress($res));
        return $res;
    }

    /**
     * @param $endDate
     * @param $startDate
     * @return mixed
     */
    public function diff($endDate, $startDate)
    {
        $dateEnd = DateTime::createFromFormat('Y-m-d H:i:s.u', $endDate);
        $dateStart = DateTime::createFromFormat('Y-m-d H:i:s.u', $startDate);

        return $this->dateTimeToMilliseconds($dateEnd) - $this->dateTimeToMilliseconds($dateStart);
    }

    public function dateTimeToMilliseconds(\DateTime $dateTime)
    {
        $secs = $dateTime->getTimestamp();
        $milliseconds = $secs*1000;
        $milliseconds += $dateTime->format('u')/1000;
        return $milliseconds;
    }
}