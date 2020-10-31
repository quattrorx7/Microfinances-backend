<?php

namespace app\modules\api\serializer\client;

use app\models\Client;
use app\components\serializers\AbstractProperties;
use app\modules\api\serializer\advance\AdvanceSerializer;
use app\modules\api\serializer\district\DistrictSerializer;
use app\modules\api\serializer\files\FilesSerializer;
use app\modules\api\serializer\user\OwnerSerializer;

class ClientShortSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Client::class => [
                'id',
                'name',
                'surname',
                'patronymic',
                'district'
            ],
            DistrictSerializer::class
        ];
    }

}