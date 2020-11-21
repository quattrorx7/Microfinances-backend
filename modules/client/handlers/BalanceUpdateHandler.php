<?php

namespace app\modules\client\handlers;

use app\modules\client\components\ClientService;
use app\modules\client\dto\PayDto;

class BalanceUpdateHandler extends AbstractPayHandler
{
    protected ClientService $clientService;

    public function __construct()
    {
        $this->clientService = \Yii::createObject(ClientService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        $this->clientService->updateBalanceWIthAutoPayment($dto->client, $dto->amount);
        return parent::handle($next, $dto);
    }

}