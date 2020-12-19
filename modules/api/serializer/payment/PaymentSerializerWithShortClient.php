<?php

namespace app\modules\api\serializer\payment;

use app\components\serializers\AbstractProperties;
use app\models\Payment;
use app\modules\api\serializer\client\ClientShortSerializer;

class PaymentSerializerWithShortClient extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Payment::class => [
                'id',
                'client',
                'amount',
                'todayPayed' => function(Payment $model){
                    return $model->todayPayed?true:false;
                }
            ],
            ClientShortSerializer::class
        ];
    }

}