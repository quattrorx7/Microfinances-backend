<?php

namespace app\models;

/**
 * Class User
 * @package app\models
 *
 * @property-read mixed $lastAdvances
 * @property-read bool $isSuperadmin
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

    public function getLastAdvances(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Advance::class, ['user_id' => 'id'])
            ->andOnCondition(['IS', 'deleted_at', null])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getIsSuperadmin(): bool
    {
        return $this->superadmin === self::SUPERADMIN;
    }

    public function isNotification(): bool
    {
        return $this->token != null && $this->token!='' && $this->notification;
    }
}