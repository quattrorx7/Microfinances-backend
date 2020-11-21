<?php

namespace app\modules\client\handlers;

use app\modules\client\dto\PayDto;
use app\modules\client\helpers\ClientPayHelper;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use yii\helpers\ArrayHelper;

class PaymentHandler extends AbstractPayHandler
{
    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = \Yii::createObject(PaymentService::class);
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($next) {
            $activePayments = ArrayHelper::index($dto->client->activePayments, 'advance_id');
            $sortPayments = [];

            foreach ($dto->advanceIds as $advanceId) {
                $sortPayments[$advanceId] = $activePayments[$advanceId];
                unset($activePayments[$advanceId]);
            }

            $sortPayments = array_merge($sortPayments, $activePayments);

            foreach ($sortPayments as $currentPayment) {
                $payAmount = ClientPayHelper::differenceResult($dto->amount, $currentPayment->amount);

                $this->paymentService->payByPayment($currentPayment, $payAmount);
                $dto->amount -= $payAmount;

                (new PaymentHistoryService())->saveHistory($currentPayment, $payAmount, $dto->inCart, 'payment');
            }

            if ($dto->amount <= 0) {
                $next = false;
            }
        }

        return parent::handle($next, $dto);
    }

}