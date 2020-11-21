<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\advance\components\AdvanceCalculator;
use app\modules\advance\components\AdvanceService;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\api\serializer\client\ClientSerializer;
use app\modules\client\components\ClientManager;
use app\modules\client\components\ClientService;
use app\modules\client\exceptions\ValidateClientCreateException;
use app\modules\client\exceptions\ValidateClientUpdateException;
use app\modules\client\forms\ClientCreateForm;
use app\modules\client\forms\ClientSearchForm;
use app\modules\client\forms\ClientUpdateForm;
use app\modules\client\providers\ClientProvider;
use app\modules\user\components\UserService;
use Yii;
use yii\base\Exception;

class ClientController extends AuthedApiController
{

    protected ClientService $clientService;

    protected AdvanceService $advanceService;

    protected UserService $userService;

    protected ClientManager $clientManager;

    protected ClientProvider $clientProvider;

    public function injectDependencies(
        UserService $userService,
        ClientService $clientService,
        ClientProvider $clientProvider,
        AdvanceService $advanceService,
        ClientManager $clientManager
    ): void
    {
        $this->clientService = $clientService;
        $this->clientProvider = $clientProvider;
        $this->advanceService = $advanceService;
        $this->userService = $userService;
        $this->clientManager = $clientManager;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'view' => ['GET'],
            'update' => ['POST']
        ];
    }

    /**
    * @return array
    * @throws Exception
    */
    public function actionIndex(): array
    {
        $form = ClientSearchForm::loadAndValidate(Yii::$app->request->queryParams);
        $models = $this->clientManager->getClients($form);
        return ClientSerializer::serialize($models);
    }

    /**
     * @return array
     * @throws ValidateClientCreateException
     * @throws \Exception
     */
    public function actionCreate(): array
    {
        $form = ClientCreateForm::loadAndValidate(Yii::$app->request->bodyParams);

        if ($this->isSuperadmin($this->currentUser)) {
            $advanceForm = AdvanceCreateForm::loadAndValidate(Yii::$app->request->bodyParams);

            $calculateDto = (new AdvanceCalculator())->calculate(
                $advanceForm->amount,
                $advanceForm->limitation,
                $advanceForm->daily_payment
            );

            $client = $this->clientService->createByForm($form, $this->currentUser);
            $user = $this->userService->getUser($advanceForm->user_id);
            $this->advanceService->createByForm($advanceForm, $user, $client, true);

            return ClientSerializer::serialize($client);
        }

        $advanceForm = AdvanceCreateWithClientForm::loadAndValidate(Yii::$app->request->bodyParams);

        $client = $this->clientService->createByForm($form, $this->currentUser);
        $this->advanceService->createForClient($advanceForm, $this->currentUser, $client);

        return ClientSerializer::serialize($client);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function actionView(int $id): array
    {
        $model = $this->clientService->getClient($id);
        return ClientSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     * @throws ValidateClientUpdateException
     */
    public function actionUpdate(int $id): array
    {
        $form = ClientUpdateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->clientService->getClient($id);

        if ($this->isSuperadmin($this->currentUser)) {
            //@todo тут редактирует руководитель

            return ClientSerializer::serialize($model);
        }

        $model = $this->clientService->updateByForm($model, $form);

        return ClientSerializer::serialize($model);
    }

}