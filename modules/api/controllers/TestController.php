<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\user\{UserCreateFailedValidateException, UserFailedValidateException};
use app\modules\api\serializer\user\UserSerializer;
use app\modules\user\components\UserService;
use app\modules\user\models\forms\UserCreateForm;
use Yii;
use yii\base\Exception;

class TestController extends AuthedApiController
{

    protected UserService $userService;

    public function injectDependencies(UserService $userService): void
    {
        $this->userService = $userService;
    }

    /**
     * тестовый метод для проверки АПИ (авторизованный пользователь)
     */
    public function actionIndex(): string
    {
        return 'success';
    }

    /**
     * Создание пользователя через АПИ (тестовый вариант)
     * (аналогично используется в app\modules\admin\controllers\UserController::actionCreate)
     *
     * @throws UnSuccessModelException
     * @throws UserCreateFailedValidateException
     * @throws UserFailedValidateException
     * @throws Exception
     */
    public function actionCreateUser(): array
    {
        $userForm = UserCreateForm::loadByRequestBodyParams(Yii::$app->request->bodyParams);
        $model = $this->userService->createUserFromCreateForm($userForm);

        return UserSerializer::serialize($model);
    }
}