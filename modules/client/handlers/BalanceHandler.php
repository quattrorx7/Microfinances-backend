<?php

namespace app\modules\client\handlers;

use app\modules\client\components\ClientService;
use app\modules\client\dto\PayDto;

class BalanceHandler extends AbstractPayHandler
{
    protected ClientService $clientService;

    public function __construct()
    {
        $this->clientService = \Yii::createObject(ClientService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($next) {
            $this->clientService->updateBalance($dto->client, $dto->amount);
        }

        return parent::handle($next, $dto);
    }

}