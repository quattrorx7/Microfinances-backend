<?php

namespace app\modules\client\handlers;

use app\helpers\PriceHelper;
use app\models\PaymentHistory;
use app\modules\client\components\ClientService;
use app\modules\client\dto\PayDto;
use app\modules\payment\components\PaymentHistoryService;

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
            if($dto->amount>0){
                (new PaymentHistoryService())->saveHistoryBalance($dto->client, $dto->amount, 'payment', PaymentHistory::PAYMENT_TYPE_BALANCE);

                $dto->addMessage('Резерв начислен: '.PriceHelper::priceFormat($dto->amount));
            }
        }

        return parent::handle($next, $dto);
    }

}