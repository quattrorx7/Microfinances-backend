<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\api\serializer\client\ClientSerializer;

class AdvanceDebtSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'client',
                'amount' => function(Advance $model){
                    return $model->client->getActivePaymentsSum();
                },
                'todayPayed' => function(Advance $model){
                    return $model->todayPayed?true:false;
                }
            ],
            ClientSerializer::class,
        ];
    }

}