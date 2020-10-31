<?php

namespace app\modules\advance\formatters;

use app\models\Advance;
use app\modules\advance\helpers\AdvanceHelper;

class AdvanceStatusFormatter
{
    public static function formatter(Advance $model): array
    {
        return [
            'text' => AdvanceHelper::getStatusById($model->status),
            'name' => $model->status
        ];
    }
}