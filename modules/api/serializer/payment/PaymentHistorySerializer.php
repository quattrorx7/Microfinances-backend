<?php

namespace app\modules\api\serializer\payment;

use app\components\serializers\AbstractProperties;
use app\models\PaymentHistory;

class PaymentHistorySerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            PaymentHistory::class => [
                'created_at',
                'message',
                'amount',
                'debt',
            ]
        ];
    }
}