<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\helpers\DateHelper;
use app\modules\advance\components\AdvanceService;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use yii\base\Exception;
use Yii;

class StatisticsController extends AuthedApiController
{

    protected PaymentService $paymentService;

    protected PaymentHistoryService $paymentHistoryService;

    protected AdvanceService $advanceService;


    public function injectDependencies(
        PaymentService $paymentService,
        PaymentHistoryService $paymentHistoryService,
        AdvanceService $advanceService
    ): void
    {
        $this->paymentService = $paymentService;
        $this->paymentHistoryService = $paymentHistoryService;
        $this->advanceService = $advanceService;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['POST']
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionIndex(): array
    {
        $form = Yii::$app->request->bodyParams;
        
        $from = isset($form['from'])?DateHelper::formatDate($form['from'], 'Y-m-d'):null;
        $to = isset($form['to'])?DateHelper::formatDate($form['to'], 'Y-m-d'):null;
        $payments = $this->paymentHistoryService->getStatisticPayment($from, $to);
        $debts = $this->paymentHistoryService->getStatisticDebt($from, $to);

        $issuedAdvance = $this->advanceService->statisticIssuedAdvance($from, $to);
        $updateAdvance = $this->advanceService->statisticRefinancingdAdvance($from, $to);;

        return [
            'debts'=>$debts,
            'payments'=>$payments,
            'update_advance' => $updateAdvance,
            'issued_advance' => $issuedAdvance
        ];
    }
}