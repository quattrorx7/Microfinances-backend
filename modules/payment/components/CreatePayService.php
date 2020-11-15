<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\models\User;

class CreatePayService extends BaseService
{

    protected PaymentFactory $paymentFactory;

    protected PaymentRepository $paymentRepository;

    protected PaymentPopulator $paymentPopulator;

    public function injectDependencies(PaymentFactory $paymentFactory, PaymentRepository $paymentRepository, PaymentPopulator $paymentPopulator): void
    {
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
    }

    public function execute(User $user)
    {

    }
}