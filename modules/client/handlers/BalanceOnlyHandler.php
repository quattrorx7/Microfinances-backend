<?php

namespace app\modules\client\handlers;

use app\helpers\PriceHelper;
use app\models\PaymentHistory;
use app\modules\client\components\ClientService;
use app\modules\client\dto\PayDto;
use app\modules\payment\components\PaymentHistoryService;

class BalanceOnlyHandler extends AbstractPayHandler
{
    protected ClientService $clientService;

    public function __construct()
    {
        $this->clientService = \Yii::createObject(ClientService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($next) {
            $type = $dto->inCart ? PaymentHistory::PAYMENT_TYPE_CARD_BALANCE : PaymentHistory::PAYMENT_TYPE_CASH_BALANCE;

            (new PaymentHistoryService())->saveHistoryBalance($dto->user, $dto->client, $dto->amount, $dto->inCart, 'payment', $type);
            $this->clientService->updateBalance($dto->client, $dto->amount);

            $dto->addMessage('Резерв начислен: '.PriceHelper::priceFormat($dto->amount));
        }

        return parent::handle($next, $dto);
    }

}