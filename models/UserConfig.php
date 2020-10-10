<?php

namespace app\models;

use Yii;

/**
 *
 * @property string $username
 * @property bool $isSuperadmin
 */
class UserConfig extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public $identityClass = User::class;

    /**
     * @inheritdoc
     */
    public $enableAutoLogin = true;

    /**
     * @inheritdoc
     */
    public int $cookieLifetime = 2592000;

    /**
     * @inheritdoc
     */
    public $loginUrl = ['/login'];

    /**
     * Allows to call Yii::$app->user->isSuperadmin
     *
     * @return bool
     */
    public function getIsSuperadmin(): bool
    {
        return Yii::$app->user->identity->superadmin === 1;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return Yii::$app->user->identity->username;
    }
}