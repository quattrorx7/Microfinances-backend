<?php

namespace app\modules\advance\formatters;

use app\models\Advance;
use app\modules\advance\helpers\AdvanceHelper;

class AdvanceStatusFormatter
{
    public static function formatter(Advance $model): array
    {
        if($model->isRefinancing() && ($model->status==Advance::STATE_SENT || $model->status==Advance::STATE_ISSUED)) {
            return [
                'text' => "Реф",
                'name' => $model->status
            ];
        }
        return [
            'text' => AdvanceHelper::getStatusById($model->status),
            'name' => $model->status
        ];
    }
}