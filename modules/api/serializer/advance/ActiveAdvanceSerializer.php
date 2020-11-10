<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceStatusFormatter;
use app\modules\api\serializer\files\FilesSerializer;

class ActiveAdvanceSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'issue_date',
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