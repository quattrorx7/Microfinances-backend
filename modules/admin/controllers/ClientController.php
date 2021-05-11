<?php

namespace app\modules\admin\controllers;

use app\modules\client\providers\ClientProvider;
use Yii;
use app\components\controllers\AuthedAdminController;
use app\models\District;
use app\modules\client\components\ClientService;
use app\modules\client\forms\ClientChangeUserForm;
use app\modules\client\forms\ClientCreateForm;
use app\modules\client\forms\ClientOwnerForm;
use app\modules\user\components\UserRepository;

/**
 * Class ClientController
 * @package app\modules\admin\controllers
 */
class ClientController extends AuthedAdminController
{

    protected ClientProvider $clientProvider;

    protected ClientService $clientService;

    protected UserRepository $userRepository;

    public function injectDependencies(ClientProvider $clientProvider, ClientService $clientService, UserRepository $userRepository): void
    {
        $this->clientProvider = $clientProvider;
        $this->clientService = $clientService;
        $this->userRepository = $userRepository;
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        [$searchModel, $dataProvider] = $this->clientProvider->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     * @throws UnSuccessModelException
     */
    public function actionCreate()
    {
        $form = new ClientCreateForm();
        $form->load(Yii::$app->request->post());

        $form2 = new ClientOwnerForm();
        $form2->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $form->validate() && $form2->validate()) {
            $user = $this->userRepository->getUserById((int)$form2->user_id);

            $this->clientService->createByForm($form, $user, false);
            return $this->redirect(['index']);
        }

        $districts = District::find()->all();
        $users = $this->userRepository->getWithoutAdminBySearch('');

        return $this->render('create', [
            'model' => $form,
            'model2' => $form2,
            'districts' => $districts,
            'users' => $users,
        ]);
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionChange($id)
    {
        $form = new ClientChangeUserForm();
        $form->load(Yii::$app->request->post());
        $client = $this->clientService->getClient($id);

        if (Yii::$app->request->isPost && $form->validate()){
            
            $this->clientService->changeUser($client, $form->user_id);

            return $this->redirect(['index']);
        }else{
            $form->user_id = $client->owner_id;
        }

        $users = $this->userRepository->getWithoutAdminBySearch('');

        return $this->render('change', [
            'model' => $form,
            'users' => $users,
        ]);
    }
}
