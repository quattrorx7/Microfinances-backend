<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\formatters\AdvanceStatusFormatter;

class AdvanceSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'created_at',
                'amount',
                'limitation',
                'status' => function(Advance $model) {
                    return AdvanceStatusFormatter::formatter($model);
                }
            ]
        ];
    }

}