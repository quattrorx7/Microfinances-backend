<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceIssueDateFormatter;
use app\modules\advance\formatters\AdvanceStatusFormatter;
use app\modules\api\serializer\files\FilesSerializer;

class AdvanceSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'issue_date' => function(Advance $advance) {
                    return AdvanceIssueDateFormatter::formatter($advance);
                },
                'created_at',
                'amount',
                'limitation',
                'status' => function(Advance $model) {
                    return AdvanceStatusFormatter::formatter($model);
                },
                'note'
            ],
            FilesSerializer::class
        ];
    }

}