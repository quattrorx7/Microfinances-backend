<?php

namespace app\modules\api\serializer\payment;

use app\components\serializers\AbstractProperties;
use app\models\Payment;
use app\modules\api\serializer\client\ClientSerializer;

class PaymentSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Payment::class => [
                'client',
                'amount',
            ],
            ClientSerializer::class
        ];
    }

}