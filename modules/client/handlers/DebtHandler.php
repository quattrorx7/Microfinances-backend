<?php

namespace app\modules\client\handlers;

use app\helpers\PriceHelper;
use app\models\PaymentHistory;
use app\modules\client\dto\PayDto;
use app\modules\client\helpers\ClientPayHelper;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use Exception;

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
            $isDebt = false;
            foreach ($dto->client->lastDebtPayments as $debtModel) {
                $isDebt = true;
                $payAmount = ClientPayHelper::differenceResult($dto->amount, $debtModel->amount);

                $this->paymentService->payByPayment($debtModel, $payAmount);
                $dto->amount -= $payAmount;

                if($dto->inCart===null){
                    $type = PaymentHistory::PAYMENT_TYPE_AUTO;
                }else{
                    $type = $dto->inCart ? PaymentHistory::PAYMENT_TYPE_CARD : PaymentHistory::PAYMENT_TYPE_CASH;
                }

                (new PaymentHistoryService())->saveHistory($dto->client, $debtModel, $payAmount, $dto->inCart, 'payment', $type);
            }

            if ($dto->amount <= 0) {
                $next = false;
            }
            if($isDebt){
                $dto->addMessage('Долг погашен');
                
                $sum = $dto->client->getActiveandAndDebtPaymentsSum();
                if($sum>0 && $dto->amount <= 0)
                    $dto->addMessage('Остаток на сегодня: '.PriceHelper::priceFormat($sum));
            }
        }

        return parent::handle($next, $dto);
    }

}