<?php

namespace app\models;

use Yii;

trait UserIdentity
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->where(['auth_key' => $token])
            ->andWhere(['IS', 'deleted_at', null])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): ?User
    {
        return static::find()
            ->where(['username' => $username])
            ->andWhere(['IS', 'deleted_at', null])
            ->one();
    }

    /**
     * Finds user by confirmation token
     *
     * @param  string      $token confirmation token
     * @return static|null|User
     */
    public static function findByConfirmationToken($token)
    {
        $parts     = explode('_', $token);
        $timestamp = (int)end($parts);

        if ( $timestamp + 3600 < time() )
        {
            // token expired
            return null;
        }

        return static::findOne([
            'confirmation_token' => $token,
            'status'             => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by confirmation token
     *
     * @param  string      $token confirmation token
     * @return static|null|User
     */
    public static function findInactiveByConfirmationToken($token)
    {
        $parts     = explode('_', $token);
        $timestamp = (int)end($parts);

        if ( $timestamp + 3600 < time() )
        {
            return null;
        }

        return static::findOne([
            'confirmation_token' => $token,
            'status'             => User::STATUS_INACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new confirmation token
     */
    public function generateConfirmationToken(): void
    {
        $this->confirmation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes confirmation token
     */
    public function removeConfirmationToken(): void
    {
        $this->confirmation_token = null;
    }
}