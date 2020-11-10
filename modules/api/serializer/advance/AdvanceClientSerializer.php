<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\api\serializer\district\DistrictSerializer;

class AdvanceClientSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id' => 'client_id',
                'name' => 'client.name',
                'surname' => 'client.surname',
                'district' => 'client.district'
            ],
            DistrictSerializer::class
        ];
    }

}