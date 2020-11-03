<?php

namespace app\modules\client\components;

use app\components\BaseService;
use app\modules\client\forms\ClientSearchForm;

class ClientManager extends BaseService
{

    protected ClientRepository $clientRepository;

    public function injectDependencies(ClientRepository $clientRepository): void
    {
        $this->clientRepository = $clientRepository;
    }

    public function getClients(ClientSearchForm $form)
    {
        return $this->clientRepository->getBySearch($form->search);
    }
}