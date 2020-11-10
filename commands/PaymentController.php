<?php

namespace app\commands;

use app\helpers\DateHelper;
use app\modules\advance\components\AdvanceService;
use app\modules\payment\components\PaymentService;
use yii\console\Controller;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    protected AdvanceService $advanceService;

    public function injectDependencies(
        PaymentService $paymentService,
        AdvanceService $advanceService
    ): void
    {
        $this->paymentService = $paymentService;
        $this->advanceService = $advanceService;
    }

    /** создание платежок на сегодняшний день */
    public function actionCreate(): void
    {
        $models = $this->advanceService
            ->getActiveAdvancesByDate(DateHelper::nowWithoutHours());

        $this->paymentService
            ->generatePaymentData($models);
    }
}
