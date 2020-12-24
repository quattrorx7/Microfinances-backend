<?php

namespace app\modules\client\handlers;

use app\helpers\PriceHelper;
use app\modules\client\dto\PayDto;
use app\modules\client\helpers\ClientPayHelper;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use Exception;

class EmptyHandler extends AbstractPayHandler
{
    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = \Yii::createObject(PaymentService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($next) {
            $debtModel = $dto->client->lastDebtPayments[0];
           
            (new PaymentHistoryService())->saveHistory($dto->client, $debtModel, 0, $dto->inCart);

        }

        return parent::handle($next, $dto);
    }

}