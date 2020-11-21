<?php

namespace app\modules\client\handlers;

use app\modules\client\dto\PayDto;
use app\modules\client\helpers\ClientPayHelper;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;

class DebtHandler extends AbstractPayHandler
{
    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = \Yii::createObject(PaymentService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($next) {
            foreach ($dto->client->lastDebtPayments as $debtModel) {
                $payAmount = ClientPayHelper::differenceResult($dto->amount, $debtModel->amount);

                $this->paymentService->payByPayment($debtModel, $payAmount);
                $dto->amount -= $payAmount;

                (new PaymentHistoryService())->saveHistory($debtModel, $payAmount, $dto->inCart);
            }

            if ($dto->amount <= 0) {
                $next = false;
            }
        }

        return parent::handle($next, $dto);
    }

}