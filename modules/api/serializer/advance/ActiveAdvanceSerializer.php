<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceIssueDateFormatter;
use app\modules\api\serializer\files\FilesSerializer;

class ActiveAdvanceSerializer extends AbstractProperties
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
                'payment_left',
                'daily_payment',
                'note'
            ],
            FilesSerializer::class
        ];
    }

}