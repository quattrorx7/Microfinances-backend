<?php

namespace app\components\controllers;

use app\components\filters\HttpHeaderAuth;
use app\models\User;
use Yii;
use yii\web\IdentityInterface;

/**
 *
 * @property IdentityInterface|User $currentUser
 */
class AuthedApiController extends BaseApiController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpHeaderAuth::class,
            'header' => 'Access-Token'
        ];

        return $behaviors;
    }

    /**
     * @return User|IdentityInterface
     */
    public function getCurrentUser(): User
    {
        return Yii::$app->user->identity;
    }

    public function isSuperadmin(User $user): bool
    {
        return $user->superadmin === User::SUPERADMIN;
    }
}