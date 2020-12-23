<?php

namespace app\modules\user\components;

use app\components\BaseService;
use app\models\User;
use app\modules\user\forms\UserSearchForm;
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

    public function getUsers(UserSearchForm $form){
        return $this->userRepository->getBySearch($form->search);
    }

    public function getUsersWithoutAdmin(UserSearchForm $form){
        return $this->userRepository->getWithoutAdminBySearch($form->search);
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