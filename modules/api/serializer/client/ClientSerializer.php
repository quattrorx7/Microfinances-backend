<?php

namespace app\modules\api\serializer\client;

use app\models\Client;
use app\components\serializers\AbstractProperties;
use app\modules\api\serializer\district\DistrictSerializer;
use app\modules\api\serializer\files\FilesSerializer;
use app\modules\api\serializer\user\OwnerSerializer;

class ClientSerializer extends AbstractProperties

{

    public function getProperties(): array
    {
        return [
            Client::class => [
                'id',
                'name',
                'surname',
                'patronymic',
                'phone',
                'additional_phone',
                'district',
                'files',
                'owner',
                'activity',
                'profit',
                'comment'
            ],
            DistrictSerializer::class,
            FilesSerializer::class,
            OwnerSerializer::class
        ];
    }

}