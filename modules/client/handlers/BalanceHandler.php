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
            if($dto->fromBalance){
            //Оплата с баланса
                $this->clientService->updateBalance($dto->client, -1*($dto->startAmount - $dto->amount));
            }else{
                $this->clientService->updateBalance($dto->client, $dto->amount);
                if($dto->amount>0){
                    $type = $dto->inCart ? PaymentHistory::PAYMENT_TYPE_CARD_BALANCE : PaymentHistory::PAYMENT_TYPE_CASH_BALANCE;

                    (new PaymentHistoryService())->saveHistory($dto->user, $dto->client, $dto->first_advance, $dto->amount, $dto->inCart, 'payment', $type);

                    $dto->addMessage('Резерв начислен: '.PriceHelper::priceFormat($dto->amount));
                }
            }
        }

        return parent::handle($next, $dto);
    }

}