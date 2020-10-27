<?php

namespace app\modules\admin\controllers;

use app\components\exceptions\UnSuccessModelException;
use app\modules\user\components\UserService;
use app\modules\user\exceptions\UserNotFoundException;
use app\modules\user\exceptions\ValidateUserUpdateException;
use app\modules\user\forms\UserCreateForm;
use app\modules\user\forms\UserUpdateForm;
use app\modules\user\providers\UserProvider;
use Yii;
use app\components\controllers\AuthedAdminController;
use yii\base\Exception;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AuthedAdminController
{

    protected UserService $userService;

    protected UserProvider $userProvider;

    public function injectDependencies(UserService $userService, UserProvider $userProvider): void
    {
        $this->userService = $userService;
        $this->userProvider = $userProvider;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        [$searchModel, $dataProvider] = $this->userProvider
            ->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws UserNotFoundException
     */
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->userService->getUser($id),
        ]);
    }

    /**
     * @return string|Response
     * @throws UnSuccessModelException
     * @throws Exception
     */
    public function actionCreate()
    {
        $form = new UserCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $model = $this->userService->createByForm($form);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws UnSuccessModelException
     * @throws UserNotFoundException
     * @throws ValidateUserUpdateException
     */
    public function actionUpdate($id)
    {
        $model = $this->userService->getUser($id);
        $form = UserUpdateForm::loadAndValidate($model->attributes);

        if (Yii::$app->request->isPost) {

            $form->load(Yii::$app->request->post());
            $model = $this->userService->updateByForm($model, $form);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }
}
