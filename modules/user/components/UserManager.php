<?php

namespace app\modules\user\components;

use app\components\BaseService;
use app\models\User;
use app\modules\user\exceptions\UserNotFoundException;

/**
 *
 * @property-read mixed $allUsers
 */
class UserManager extends BaseService
{

    protected UserRepository $userRepository;

    public function injectDependencies(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    /**
     * @param int $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserById(int $userId): User
    {
        return $this->userRepository->getUserById($userId);
    }

}