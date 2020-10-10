<?php

namespace app\modules\api\serializer\user;

use app\models\User;
use app\components\serializers\AbstractProperties;

class UserSerializer extends AbstractProperties

{

    public function getProperties(): array
    {
        return [
            User::class => [

            ]
        ];
    }

}