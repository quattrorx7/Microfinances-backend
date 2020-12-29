<?php

namespace app\modules\api\serializer\payment;

use app\components\serializers\AbstractProperties;
use app\models\PaymentHistory;
use app\modules\api\serializer\client\ClientShortSerializer;

class PaymentHistoryWithShortClientSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            PaymentHistory::class => [
                'id',
                'created_at',
                'message',
                'amount',
                'debt',
                'client',
            ],
            ClientShortSerializer::class
        ];
    }
}