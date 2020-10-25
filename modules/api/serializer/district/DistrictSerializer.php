<?php

namespace app\modules\api\serializer\district;

use app\models\District;
use app\components\serializers\AbstractProperties;

class DistrictSerializer extends AbstractProperties

{

    public function getProperties(): array
    {
        return [
            District::class => [

            ]
        ];
    }

}