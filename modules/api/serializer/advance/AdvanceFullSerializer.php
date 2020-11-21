<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceIssueDateFormatter;
use app\modules\advance\formatters\AdvanceStatusFormatter;
use app\modules\api\serializer\client\ClientSerializer;
use app\modules\api\serializer\files\FilesSerializer;

class AdvanceFullSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'issue_date' => function(Advance $advance) {
                    return AdvanceIssueDateFormatter::formatter($advance);
                },
                'amount',
                'limitation',
                'status' => function(Advance $model) {
                    return AdvanceStatusFormatter::formatter($model);
                },
                'client'
            ],
            ClientSerializer::class
        ];
    }

}