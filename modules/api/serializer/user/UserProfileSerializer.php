<?php

namespace app\modules\api\serializer\user;

use app\models\User;
use app\components\serializers\AbstractProperties;

class UserProfileSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            User::class => [
                'id',
                'fullname',
                'username',
                'todayAdvanceCount' => static function(User $user) {
                    return 0;
                },
                'todayAdvanceAllSumma' => static function(User $user) {
                    return 0;
                },
                'todayAdvanceCardSumma' => static function(User $user) {
                    return 0;
                },
                'lastAdvancePayments' => static function(User $user) {
                    return [];
                },
            ]
        ];
    }

}