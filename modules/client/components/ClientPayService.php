<?php

namespace app\modules\client\components;

use app\components\BaseService;
use app\modules\client\dto\PayDto;
use app\modules\client\handlers\BalanceHandler;
use app\modules\client\handlers\DebtHandler;
use app\modules\client\handlers\PaymentHandler;
use app\modules\client\handlers\StartHandler;

class ClientPayService extends BaseService
{
    public function pay(PayDto $dto)
    {
        $startHandler = new StartHandler();
        $debtHandler = new DebtHandler();
        $paymentHandler = new PaymentHandler();
        $balanceHandler = new BalanceHandler();

        $startHandler
            ->setNext($debtHandler)
            ->setNext($paymentHandler)
            ->setNext($balanceHandler);

        $dto->addMessage('Оплата принята');
        $startHandler->handle(true, $dto);
    }

}