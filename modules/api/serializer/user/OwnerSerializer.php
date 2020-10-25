<?php

namespace app\modules\api\serializer\user;

use app\models\User;
use app\components\serializers\AbstractProperties;

class OwnerSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            User::class => [
                'id',
                'fullname',
                'username'
            ]
        ];
    }

}