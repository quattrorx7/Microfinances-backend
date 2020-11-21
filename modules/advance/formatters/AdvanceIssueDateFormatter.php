<?php

namespace app\modules\advance\formatters;

use app\helpers\DateHelper;
use app\models\Advance;

class AdvanceIssueDateFormatter
{
    public static function formatter(Advance $model): string
    {
        return DateHelper::formatDate($model->issue_date, 'Y-m-d');
    }
}