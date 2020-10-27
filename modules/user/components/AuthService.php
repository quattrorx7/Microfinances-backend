<?php

namespace app\modules\user\components;

use app\components\BaseService;
use app\models\User;
use app\models\UserAuthToken;
use app\modules\user\exceptions\UserNotFoundException;
use app\modules\user\exceptions\ValidateAuthException;
use app\modules\user\forms\AuthForm;
use Yii;
use yii\base\Exception;

class AuthService extends BaseService
{

    protected UserRepository $userRepository;
    protected UserAuthTokenRepository $userAuthTokenRepository;

    public function injectDependencies(UserRepository $userRepository, UserAuthTokenRepository $userAuthTokenRepository): void
    {
        $this->userRepository = $userRepository;
        $this->userAuthTokenRepository = $userAuthTokenRepository;
    }

    /**
     * @param AuthForm $form
     * @return User
     * @throws ValidateAuthException
     * @throws UserNotFoundException|Exception
     */
    public function authByForm(AuthForm $form): User
    {
        $user = $this->userRepository
            ->getActiveUserByUsername($form->id);

        if (!$user->validatePassword($form->password)) {
            throw new ValidateAuthException($form, 'Неправильный логин или пароль');
        }

        $tokenModel = $this->createAuthToken($user, $form->device_id);
        $user->setCurrentApiAuthToken($tokenModel);

        return $user;
    }

    /**
     * @param User $user
     * @param string $deviceId
     * @return UserAuthToken
     * @throws Exception
     */
    public function createAuthToken(User $user, string $deviceId): UserAuthToken
    {
        $model = $this->userAuthTokenRepository->getByDeviceId($deviceId);

        if (!$model) {
            $model = new UserAuthToken();
            $model->device_id = $deviceId;
        }
        $model->populateRelation('user', $user);
        $model->status_id = UserAuthToken::STATUS_ACTIVE;
        $model->auth_key = \Yii::$app->security->generateRandomString(32);

        $this->userAuthTokenRepository->saveToken($model);
        return $model;
    }

}