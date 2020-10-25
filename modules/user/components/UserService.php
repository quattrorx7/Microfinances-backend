<?php

namespace app\modules\user\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\User;
use app\modules\user\exceptions\UserNotFoundException;
use app\modules\user\forms\UserCreateForm;
use app\modules\user\forms\UserUpdateForm;
use yii\base\Exception;
use yii\db\StaleObjectException;

class UserService extends BaseService
{

    protected UserFactory $userFactory;

    protected UserRepository $userRepository;

    protected UserPopulator $userPopulator;

    public function injectDependencies(UserFactory $userFactory, UserRepository $userRepository, UserPopulator $userPopulator): void
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->userPopulator = $userPopulator;
    }

    /**
     * @param UserCreateForm $form
     * @return User
     * @throws UnSuccessModelException
     * @throws Exception
     */
    public function createByForm(UserCreateForm $form): User
    {
        $model = $this->userFactory->create();
        $this->userPopulator
            ->populateFromCreateForm($model, $form);

        $model->setPassword($form->password);
        $this->userRepository->save($model);
        return $model;
    }

    /**
    * @param User $model
    * @param UserUpdateForm $form
    * @return User
    * @throws UnSuccessModelException
    */
    public function updateByForm(User $model, UserUpdateForm $form): User
    {
        $this->userPopulator
            ->populateFromUpdateForm($model, $form);

        $this->userRepository->save($model);

        return $model;
    }

    /**
    * @param $id
    * @return User|array|\yii\db\ActiveRecord
    * @throws UserNotFoundException
    */
    public function getUser($id)
    {
        return $this->userRepository
            ->getUserById($id);
    }

    /**
    * @param User $model
    * @throws \Throwable
    * @throws StaleObjectException
    */
    public function deleteUser(User $model): void
    {
        $model->delete();
    }

    public function mapAllByIdAndUsername()
    {

    }
}