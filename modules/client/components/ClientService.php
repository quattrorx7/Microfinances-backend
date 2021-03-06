<?php

namespace app\modules\client\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\Client;
use app\models\User;
use app\modules\client\exceptions\ClientNotFoundException;
use app\modules\client\exceptions\ValidateClientCreateException;
use app\modules\client\forms\ClientCreateForm;
use app\modules\client\forms\ClientFileForm;
use app\modules\client\forms\ClientUpdateForm;
use app\modules\client\validators\ClientPhoneValidator;
use app\modules\district\components\DistrictService;
use app\modules\district\exceptions\DistrictNotFoundException;
use Exception;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

class ClientService extends BaseService
{

    protected ClientFactory $clientFactory;

    protected ClientRepository $clientRepository;

    protected ClientPopulator $clientPopulator;

    protected DistrictService $districtService;

    public function injectDependencies(
        ClientFactory $clientFactory,
        ClientRepository $clientRepository,
        ClientPopulator $clientPopulator,
        DistrictService $districtService
    ): void
    {
        $this->clientFactory = $clientFactory;
        $this->clientRepository = $clientRepository;
        $this->clientPopulator = $clientPopulator;
        $this->districtService = $districtService;
    }

    /**
     * @param ClientCreateForm $form
     * @param User $owner
     * @return Client
     * @throws UnSuccessModelException
     * @throws Exception
     */
    public function createByForm(ClientCreateForm $form, User $owner): Client
    {
        $model = $this->clientFactory->create($owner);
        $district = $this->districtService->getDistrict($form->district_id);

        $model = $this->clientPopulator
            ->populateFromCreateForm($model, $form)
            ->populateDistrict($model, $district)
            ->populateFiles($model, UploadedFile::getInstancesByName('files'))
            ->getModel($model);

        if (!(new ClientPhoneValidator())->validate($model, 'phone')) {
            throw new ValidateClientCreateException($model);
        }

        if (!(new ClientPhoneValidator())->validate($model, 'additional_phone')) {
            throw new ValidateClientCreateException($model);
        }

        $this->clientRepository->saveClient($model);

        return $model;
    }

    public function loadFiles(int $clientId, ClientFileForm $form)
    {
        $model = $this->clientRepository->getClientById($clientId);

        $model = $this->clientPopulator
            ->populateFiles($model, $form->files)
            ->getModel($model);

        $this->clientRepository->saveClient($model);

        return $model;
    }

    /**
     * @param Client $model
     * @param ClientUpdateForm $form
     * @return Client
     * @throws UnSuccessModelException
     * @throws DistrictNotFoundException|\yii\db\Exception
     */
    public function updateByForm(Client $model, ClientUpdateForm $form): Client
    {
        $district = $this->districtService->getDistrict($form->district_id);

        $this->clientPopulator
            ->populateFromUpdateForm($model, $form)
            ->populateDistrict($model, $district)
            ->populateFiles($model, UploadedFile::getInstancesByName('files'))
            ->getModel($model);

        if (!(new ClientPhoneValidator())->validate($model, 'phone')) {
            throw new ValidateClientCreateException($model);
        }

        if (!(new ClientPhoneValidator())->validate($model, 'additional_phone')) {
            throw new ValidateClientCreateException($model);
        }

        $this->clientRepository->saveClient($model);

        return $model;
    }

    /**
    * @param $id
    * @return Client|array|\yii\db\ActiveRecord
    * @throws ClientNotFoundException
    */
    public function getClient($id)
    {
        return $this->clientRepository->getClientById($id);
    }

    /**
    * @param Client $model
    * @throws \Throwable
    * @throws StaleObjectException
    */
    public function deleteClient(Client $model): void
    {
        $model->delete();
    }

    public function updateBalance(Client $model, int $amount): void
    {
        $model->balance += $amount;
        $this->clientRepository->save($model);
    }

    public function updateBalanceWIthAutoPayment(Client $model, int $amount): void
    {
        $model->balance = $amount;
        $this->clientRepository->save($model);
    }
}