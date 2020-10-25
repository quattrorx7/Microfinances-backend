<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\api\serializer\client\ClientSerializer;
use app\modules\client\components\ClientService;
use app\modules\client\exceptions\ValidateClientCreateException;
use app\modules\client\exceptions\ValidateClientUpdateException;
use app\modules\client\forms\ClientCreateForm;
use app\modules\client\forms\ClientUpdateForm;
use app\modules\client\providers\ClientProvider;
use Yii;
use yii\base\Exception;

class ClientController extends AuthedApiController
{

    protected ClientService $clientService;

    protected ClientProvider $clientProvider;

    public function injectDependencies(ClientService $clientService, ClientProvider $clientProvider): void
    {
        $this->clientService = $clientService;
        $this->clientProvider = $clientProvider;
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
        [$searchModel, $dataProvider] = $this->clientProvider->search(Yii::$app->request->queryParams);
        return ClientSerializer::serialize($dataProvider->getModels());
    }

    /**
     * @return array
     * @throws ValidateClientCreateException
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $form = ClientCreateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->clientService->createByForm($form, $this->currentUser);

        return ClientSerializer::serialize($model);
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
            // тут редактирует руководитель

            return ClientSerializer::serialize($model);
        }

        $model = $this->clientService->updateByForm($model, $form);

        return ClientSerializer::serialize($model);
    }

}