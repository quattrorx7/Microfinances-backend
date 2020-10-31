<?php

namespace app\modules\user\components;

use app\components\BaseService;

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

}