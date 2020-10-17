<?php

namespace app\modules\api\controllers;

use app\components\controllers\BaseApiController;
use app\components\JSendResponse;
use app\modules\api\serializer\user\UserSerializer;
use app\modules\user\components\AuthService;
use app\modules\user\forms\AuthForm;
use yii\base\Exception;
use Yii;

class AuthController extends BaseApiController
{

    protected AuthService $authService;

    public function injectDependencies(AuthService $authService): void
    {
        $this->authService = $authService;
    }

    protected function verbs(): array
    {
        return [
            'login' => ['POST'],
            'register' => ['POST'],
            'logout' => ['POST'],
        ];
    }


    /**
     * @throws Exception
     */
    public function actionLogin(): array
    {
        $form = AuthForm::loadAndValidate(Yii::$app->request->bodyParams);
        $user = $this->authService->authByForm($form);

        return UserSerializer::serialize($user);
    }

    public function actionLogout(): JSendResponse
    {
        return JSendResponse::success('Сессия успешно завершена');
    }

}