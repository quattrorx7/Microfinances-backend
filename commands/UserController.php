<?php

namespace app\commands;

use app\helpers\DateHelper;
use yii\base\DynamicModel;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class UserController extends Controller
{
    public function actionCreate(): int
    {
        echo 'Enter email: ' . PHP_EOL;
        $data['email'] = fgets(STDIN);

        echo 'Enter username: ' . PHP_EOL;
        $data['username'] = fgets(STDIN);

        echo 'Enter password: ' . PHP_EOL;
        $data['password'] = fgets(STDIN);

        echo 'Is admin? (Y/n): : ' . PHP_EOL;
        $isAdmin = fgets(STDIN);

        $data['isAdmin'] = strtoupper(trim($isAdmin)) === 'Y' ? User::SUPERADMIN : 0;

        $model = new DynamicModel(['email', 'username', 'password', 'isAdmin']);
        $model
            ->addRule(['email', 'password', 'username'], 'required')
            ->addRule(['email', 'password', 'username'], 'trim')
            ->addRule(['email'], 'email')
            ->addRule('isAdmin', 'in', ['range' => [0, 1]])
            ->addRule('password', 'string', ['max' => 32]);

        $model->load($data, '');

        if (!$model->validate()) {
            echo $this->ansiFormat('Validate error <<' . $model->getErrorSummary(true)[0] . '>>'.PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user = new User();
        $user->username = $model->username;
        $user->email = $model->email;
        $user->email_confirmed = 1;
        $user->superadmin = $model->isAdmin;
        $user->status = User::STATUS_ACTIVE;
        $user->created_at = DateHelper::now();
        $user->setPassword($model->password);
        $user->generateAuthKey();
        $user->save();

        echo $this->ansiFormat('User created', Console::FG_GREEN) . PHP_EOL;
        return ExitCode::OK;
    }
}
