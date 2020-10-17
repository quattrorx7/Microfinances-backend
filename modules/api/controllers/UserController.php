<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\modules\api\serializer\user\UserSerializer;
use app\modules\user\components\UserService;
use app\modules\user\exceptions\ValidateUserUpdateException;
use app\modules\user\forms\UserUpdateForm;
use Yii;
use yii\base\Exception;

class UserController extends AuthedApiController
{

    protected UserService $userService;

    public function injectDependencies(UserService $userService): void
    {
        $this->userService = $userService;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'update' => ['POST']
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionView(): array
    {
        $model = $this->currentUser;
        return UserSerializer::serialize($model);
    }

    /**
     * @return array
     * @throws Exception
     * @throws ValidateUserUpdateException
     * @throws UnSuccessModelException
     */
    public function actionUpdate(): array
    {
        $form = UserUpdateForm::loadAndValidate(Yii::$app->request->bodyParams);

        $model = $this->userService->updateByForm($this->currentUser, $form);
        return UserSerializer::serialize($model);
    }

}