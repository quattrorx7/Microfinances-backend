<?php

namespace app\modules\api\serializer\client;

use app\models\Client;
use app\components\serializers\AbstractProperties;
use app\modules\api\serializer\advance\ActiveAdvanceSerializer;
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
                'residence_address',
                'work_address',
                'owner',
                'activity',
                'profit',
                'comment',
                'activeAdvances',
                'debt' => static function(Client $client) {
                    return $client->getAllDebts();
                },
                'balance'
            ],
            DistrictSerializer::class,
            FilesSerializer::class,
            OwnerSerializer::class,
            ActiveAdvanceSerializer::class
        ];
    }

}