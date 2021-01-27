<?php

namespace app\modules\api\serializer\advance;

use app\components\serializers\AbstractProperties;
use app\helpers\DateHelper;
use app\models\Advance;
use app\modules\advance\helpers\AdvanceHelper;

class AdvanceHistorySerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'issue_date' => function(Advance $model){
                    return DateHelper::formatDate($model->issue_date, 'd.m');
                },
                'end_date' => function(Advance $model){
                    $end_date = $model->end_date?? DateHelper::formatDate(DateHelper::getModifyDate($model->issue_date, '+'.($model->limitation-1).' day'), 'Y-m-d');
                    
                    return DateHelper::formatDate($end_date, 'd.m');
                },
                'amount',
                'limitation',
                'status' => function(Advance $model){
                    return AdvanceHelper::getShortStatusById($model->status, $model->payment_status, $model);
                }
            ]
        ];
    }
}