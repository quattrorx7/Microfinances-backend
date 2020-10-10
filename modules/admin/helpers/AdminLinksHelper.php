<?php

namespace app\modules\admin\helpers;

use yii\helpers\Url;

class AdminLinksHelper
{
    public static function getMailCatcherLink(): string
    {
        $homeLink = Url::home(true);
        return str_replace(['http://', 'https://'], ['http://mail.', 'https://mail.'], $homeLink);
    }

    public static function getSupervisorLink(): string
    {
        $homeLink = Url::home(true);
        return substr($homeLink, 0, -1) . ':9771';
    }

    public static function getRabbitLink(): string
    {
        $homeLink = Url::home(true);
        return substr($homeLink, 0, -1) . ':15672';
    }
}