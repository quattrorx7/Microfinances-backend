<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceStatusFormatter;
use app\modules\api\serializer\client\ClientShortSerializer;
use app\modules\api\serializer\files\FilesSerializer;

class AdvanceListSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'status' => function(Advance $model) {
                    return AdvanceStatusFormatter::formatter($model);
                },
                'created_at',
                'amount',
                'client',
                'note'
            ],
            ClientShortSerializer::class,
            FilesSerializer::class
        ];
    }

}