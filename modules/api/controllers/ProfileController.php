<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\api\serializer\payment\PaymentHistorySerializer;
use app\modules\api\serializer\user\UserProfileSerializer;
use app\modules\payment\components\PaymentHistoryService;
use yii\base\Exception;

class ProfileController extends AuthedApiController
{

    protected PaymentHistoryService $paymentHistoryService;


    public function injectDependencies(
        PaymentHistoryService $paymentHistoryService
    ): void
    {
        $this->paymentHistoryService = $paymentHistoryService;
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
}