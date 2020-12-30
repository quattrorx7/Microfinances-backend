<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\helpers\DateHelper;
use app\modules\advance\components\AdvanceService;
use app\modules\api\serializer\advance\AdvanceHistorySerializer;
use app\modules\api\serializer\advance\AdvanceShortWithStatusSerializer;
use app\modules\api\serializer\payment\PaymentHistorySerializer;
use app\modules\api\serializer\payment\PaymentHistoryWithShortClientSerializer;
use app\modules\api\serializer\user\UserProfileSerializer;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use yii\base\Exception;

class ProfileController extends AuthedApiController
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
            'index' => ['GET']
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionIndex(): array
    {
        return UserProfileSerializer::serialize($this->currentUser);
    }

    public function actionPaymentlast()
    {
        $user = $this->currentUser;
        $list = $this->paymentHistoryService->getHistoryLast3ByUserId($user->id);

        return PaymentHistoryWithShortClientSerializer::serialize($list);
    }

    public function actionPaymentlastbyid(int $userId)
    {
        $list = $this->paymentHistoryService->getHistoryLast3ByUserId($userId);

        return PaymentHistoryWithShortClientSerializer::serialize($list);
    }

    public function actionPayments()
    {
        $user = $this->currentUser;
        $list = $this->paymentHistoryService->getHistoryByUserId($user->id);

        return PaymentHistoryWithShortClientSerializer::serialize($list);
    }

    public function actionPaymentsbyid(int $userId)
    {
        $list = $this->paymentHistoryService->getHistoryByUserId($userId);

        return PaymentHistoryWithShortClientSerializer::serialize($list);
    }

    /**
     * Профиль - История платежей текущии
     */
    public function actionPaymentscurrent()
    {
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        $user = $this->currentUser;
        return $this->paymentService->getPayments($date, $user->id);
    }

    /**
     * Профиль - История платежей текущии по id
     */
    public function actionPaymentscurrentbyid(int $userId)
    {
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        return $this->paymentService->getPayments($date, $userId);
    }

    public function actionAdvancedhistory()
    {
        $user = $this->currentUser;
        $history = $this->advanceService->getHistoryAppByUserId($user->id);

        return AdvanceShortWithStatusSerializer::serialize($history);
    }

    public function actionAdvancedhistorybyid(int $userId)
    {
        $history = $this->advanceService->getHistoryAppByUserId($userId);

        return AdvanceShortWithStatusSerializer::serialize($history);
    }

    public function actionStatistics(){
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        $user = $this->currentUser;
        $count = $this->advanceService->getTodayCount($date, $user->id);

        $payments = $this->paymentService->getTodayPaymentCount($date, $user->id);

        return ['advance_count'=>$count, 'payment'=>$payments];
    }

    public function actionStatisticsbyid(int $userId){
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        $count = $this->advanceService->getTodayCount($date, $userId);

        $payments = $this->paymentService->getTodayPaymentCount($date, $userId);

        return ['advance_count'=>$count, 'payment'=>$payments];
    }
}