<?php

namespace app\modules\api\serializer\payment;

use app\models\Payment;
use app\components\serializers\AbstractProperties;

class PaymentSerializer extends AbstractProperties

{

    public function getProperties(): array
    {
        return [
            Payment::class => [

            ]
        ];
    }

}