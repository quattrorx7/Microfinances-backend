<?php

namespace app\helpers;

use DateTime;
use DateTimeZone;
use Exception;

class DateHelper
{
    /**
     * @param string $timezone
     *
     * @return string
     * @throws Exception
     */
    public static function now($timezone = 'Europe/Moscow'): string
    {
        return (new DateTime('now', new DateTimeZone($timezone)))->format('Y-m-d H:i:s');
    }

    public static function nowWithoutHours($timezone = 'Europe/Moscow'): string
    {
        return (new DateTime('now', new DateTimeZone($timezone)))->format('Y-m-d');
    }
    /**
     * Ответ в секундах
     * todo возможно расширить до миллисекунд?
     * @param string $newDateTime
     * @param string $oldDateTime
     * @return float|int
     */
    public static function diff(string $newDateTime, string $oldDateTime)
    {
        $newDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $newDateTime);
        $oldDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $oldDateTime);

        return abs($newDateTime->getTimestamp() - $oldDateTime->getTimestamp());
    }

    /**
     * Разница между двумя датами в днях ($period)
     *
     * @param $dateStart
     * @param $dateEnd
     * @param string $period
     * @return \DatePeriod
     * @throws Exception
     */
    public function getPeriod($dateStart, $dateEnd, string $period = '1 day'): \DatePeriod
    {
        $begin = new DateTime($dateStart);
        $end = new DateTime($dateEnd);

        $interval = \DateInterval::createFromDateString($period);
        return new \DatePeriod($begin, $interval, $end);
    }

    /**
     * @param $date
     * @param string $modify
     * @return string
     * @throws Exception
     */
    public static function getModifyDate($date, $modify = '-1 week'): string
    {
        $date = new DateTime($date);
        $date->modify($modify);
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param $date
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function formatDate($date, $format = 'd.m.Y'): string
    {
        $date = new DateTime($date);
        return $date->format($format);
    }

}