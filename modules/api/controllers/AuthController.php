<?php

namespace app\modules\api\controllers;

use app\components\controllers\BaseApiController;
use app\components\exceptions\authorization\{FailedAuthorizationException, FailedValidateAuthorizationException};
use app\components\JSendResponse;
use app\modules\api\models\forms\ApiLoginForm;
use app\modules\api\serializer\user\UserSerializer;
use app\modules\user\components\AuthService;
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
     * @throws FailedAuthorizationException
     * @throws FailedValidateAuthorizationException
     * @throws Exception
     */
    public function actionLogin(): array
    {
        $loginForm = ApiLoginForm::loadByRequestBodyParams(Yii::$app->request->bodyParams);
        $user = $this->authService->loginByApiLoginForm($loginForm);

        return UserSerializer::serialize($user);
    }

    public function actionLogout(): JSendResponse
    {
        return JSendResponse::success('Сессия успешно завершена');
    }

}