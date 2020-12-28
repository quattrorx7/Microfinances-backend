<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\advance\components\AdvanceService;
use app\modules\api\serializer\advance\AdvanceHistorySerializer;
use app\modules\api\serializer\advance\AdvanceShortWithStatusSerializer;
use app\modules\api\serializer\payment\PaymentHistorySerializer;
use app\modules\api\serializer\user\UserProfileSerializer;
use app\modules\payment\components\PaymentHistoryService;
use yii\base\Exception;

class ProfileController extends AuthedApiController
{

    protected PaymentHistoryService $paymentHistoryService;

    protected AdvanceService $advanceService;


    public function injectDependencies(
        PaymentHistoryService $paymentHistoryService,
        AdvanceService $advanceService
    ): void
    {
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

        return PaymentHistorySerializer::serialize($list);
    }

    public function actionPaymentlastbyid(int $userId)
    {
        $list = $this->paymentHistoryService->getHistoryLast3ByUserId($userId);

        return PaymentHistorySerializer::serialize($list);
    }

    public function actionPayments()
    {
        $user = $this->currentUser;
        $list = $this->paymentHistoryService->getHistoryByUserId($user->id);

        return PaymentHistorySerializer::serialize($list);
    }

    public function actionPaymentsbyid(int $userId)
    {
        $list = $this->paymentHistoryService->getHistoryByUserId($userId);

        return PaymentHistorySerializer::serialize($list);
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
}