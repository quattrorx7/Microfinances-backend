<?php

namespace app\modules\user\components;

use app\models\User;
use app\components\BaseFactory;

class UserFactory extends BaseFactory

{
    /**
     * @return User
     */
    public function create(): User
    {
        return new User();
    }
}