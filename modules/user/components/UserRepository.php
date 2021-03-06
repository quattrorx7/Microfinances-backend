<?php

namespace app\modules\user\components;

use app\components\BaseRepository;
use app\models\User;
use app\modules\user\exceptions\UserNotFoundException;

class UserRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return User|array|\yii\db\ActiveRecord
    * @throws UserNotFoundException
     */
    public function getUserById(int $id)
    {
        $model = User::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new UserNotFoundException('User не найден');
        }

        return $model;
    }

    public function getAdminByEmail(string $email)
    {
        $model = User::find()
            ->where(['email' => $email])
            ->one();

        if (!$model) {
            throw new UserNotFoundException('User не найден');
        }

        return $model;
    }

    public function getActiveUserByEmail(string $email)
    {
        $model = User::find()
            ->where(['email' => $email])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->one();

        if (!$model) {
            throw new UserNotFoundException('User не найден');
        }

        return $model;
    }

    public function getAll()
    {
        return User::find()
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->andWhere(['superadmin' => User::USER])
            ->all();
    }

    public function getActiveUserByUsername(string $username)
    {
        $model = User::find()
            ->where(['username' => $username])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->one();

        if (!$model) {
            throw new UserNotFoundException('User не найден');
        }

        return $model;
    }

}