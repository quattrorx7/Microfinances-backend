<?php

namespace app\modules\files\helpers;

use Yii;

class BaseHelper
{
    /**
     * @return string
     */
    public static function webPath()
    {
        $web = Yii::getAlias('@web', false);

        if (!$web) {
            return Yii::getAlias('@app') . '/web';
        }

        return $web;
    }
}