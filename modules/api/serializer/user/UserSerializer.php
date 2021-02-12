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
                'id',
                'fullname',
                'username',
                'status',
                'notification',
                'accessToken' => static function(User $user) {
                    return $user->_currentApiAuthToken->auth_key ?? '';
                },
                'isDirector' => static function (User $user) {
                    return $user->superadmin === User::SUPERADMIN;
                }
            ]
        ];
    }

}