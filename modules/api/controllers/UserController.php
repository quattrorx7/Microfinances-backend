<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\modules\api\serializer\user\UserProfileSerializer;
use app\modules\api\serializer\user\UserSerializer;
use app\modules\user\forms\UserSearchForm;
use app\modules\user\components\UserManager;
use app\modules\user\components\UserService;
use app\modules\user\exceptions\UserNotFoundException;
use app\modules\user\exceptions\ValidateUserUpdateException;
use app\modules\user\forms\UserUpdateForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;

class UserController extends AuthedApiController
{

    protected UserService $userService;

    protected UserManager $userManager;

    public function injectDependencies(UserService $userService, UserManager $userManager): void
    {
        $this->userService = $userService;
        $this->userManager = $userManager;
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'view'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'matchCallback' => function($rule, $action){
                        return $this->currentUser->isSuperadmin;
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'matchCallback' => function($rule, $action){
                        return $this->currentUser->isSuperadmin || $this->currentUser->id === (int)Yii::$app->request->get('id');
                    }
                ],
            ],
        ];
        return $behaviors;
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
     * @param int $id
     * @return array
     * @throws Exception
     * @throws UserNotFoundException
     */
    public function actionView(int $id): array
    {
        $model = $this->userManager->getUserById($id);
        return UserProfileSerializer::serialize($model);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionIndex(): array
    {
        $form = UserSearchForm::loadAndValidate(Yii::$app->request->queryParams);
        $users = $this->userManager->getUsers($form);

        return UserSerializer::serialize($users);
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