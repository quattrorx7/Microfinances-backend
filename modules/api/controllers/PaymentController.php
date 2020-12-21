<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\JSendResponse;
use app\helpers\DateHelper;
use app\modules\api\serializer\payment\PaymentHistorySerializer;
use app\modules\api\serializer\payment\PaymentSerializer;
use app\modules\client\forms\ClientPayForm;
use app\modules\payment\components\CreatePayService;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentService;
use app\modules\payment\exceptions\ValidatePaymentCreateException;
use app\modules\payment\exceptions\ValidatePaymentUpdateException;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentUpdateForm;
use app\modules\payment\providers\PaymentProvider;
use Yii;
use yii\base\Exception;

class PaymentController extends AuthedApiController
{

    protected PaymentService $paymentService;

    protected PaymentHistoryService $paymentHistoryService;

    protected CreatePayService $createPayService;

    protected PaymentProvider $paymentProvider;

    public function injectDependencies(
        PaymentHistoryService $paymentHistoryService,
        PaymentService $paymentService,
        PaymentProvider $paymentProvider,
        CreatePayService $createPayService
    ): void
    {
        $this->paymentHistoryService = $paymentHistoryService;
        $this->paymentService = $paymentService;
        $this->createPayService = $createPayService;
        $this->paymentProvider = $paymentProvider;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'view' => ['GET'],
            'update' => ['POST'],
            'pay' => ['POST'],
            'history' => ['GET']
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionIndex()
    {
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        $collection = $this->paymentService->getGroupedPayments($date, $this->currentUser);
        return array_values($collection->payments);
    }

    public function actionClosed()
    {
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');
        $collection = $this->paymentService->getGroupedClosedPayments($date, $this->currentUser);
        return array_values($collection->payments);
    }

    public function actionDebt()
    {
        $collection = $this->paymentService->getLastDebtPayments($this->currentUser);
        return array_values($collection);
    }

    /**
     * @return array
     * @throws ValidatePaymentCreateException
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $form = PaymentCreateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->paymentService->createByForm($form);

        return PaymentSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function actionView(int $id): array
    {
        $model = $this->paymentService->getPayment($id);
        return PaymentSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     * @throws ValidatePaymentUpdateException
     */
    public function actionUpdate(int $id): array
    {
        $form = PaymentUpdateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->paymentService->getPayment($id);

        $model = $this->paymentService->updateByForm($model, $form);
        return PaymentSerializer::serialize($model);
    }

    public function actionPay(int $clientId): JSendResponse
    {
        $form = ClientPayForm::loadAndValidate(Yii::$app->request->bodyParams);
        $mes = $this->createPayService->execute($this->currentUser, $clientId, $form);
        return JSendResponse::success($mes);
    }

    public function actionHistory(int $clientId)
    {
        $history = $this->paymentHistoryService->getHistoryByClientId($clientId);

        return PaymentHistorySerializer::serialize($history);
    }
}