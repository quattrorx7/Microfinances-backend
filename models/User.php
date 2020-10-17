<?php

namespace app\models;

/**
 * Class User
 * @package app\models
 *
 * @property-write UserAuthToken $currentApiAuthToken
 */
class User extends \app\models\base\User implements \yii\web\IdentityInterface
{
    use UserIdentity;

    public CONST SUPERADMIN = 1;
    public CONST USER = 0;

    public CONST STATUS_ACTIVE = 1;
    public CONST STATUS_INACTIVE = 0;

    public ?UserAuthToken $_currentApiAuthToken = null;

    public function setCurrentApiAuthToken(UserAuthToken $model): void
    {
        $this->_currentApiAuthToken = $model;
    }
}