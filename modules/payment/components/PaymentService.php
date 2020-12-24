<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\helpers\DateHelper;
use app\models\Advance;
use app\models\Payment;
use app\models\User;
use app\modules\advance\components\AdvanceService;
use app\modules\api\serializer\payment\PaymentSerializerWithShortClient;
use app\modules\client\dto\PayDto;
use app\modules\client\forms\ClientPayForm;
use app\modules\client\handlers\BalanceUpdateHandler;
use app\modules\client\handlers\DebtHandler;
use app\modules\client\handlers\EmptyHandler;
use app\modules\client\handlers\StartHandler;
use app\modules\payment\dto\PaymentCollection;
use app\modules\payment\exceptions\PaymentNotFoundException;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentUpdateForm;
use yii\db\ActiveRecord;

class PaymentService extends BaseService
{

    protected PaymentFactory $paymentFactory;

    protected PaymentRepository $paymentRepository;

    protected PaymentPopulator $paymentPopulator;

    protected AdvanceService $advanceService;

    public function injectDependencies(
        PaymentFactory $paymentFactory,
        PaymentRepository $paymentRepository,
        PaymentPopulator $paymentPopulator,
        AdvanceService $advanceService
    ): void
    {
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
        $this->advanceService = $advanceService;
    }

    /**
    * @param PaymentCreateForm $form
    * @return Payment
    * @throws UnSuccessModelException
    */
    public function createByForm(PaymentCreateForm $form): Payment
    {
        $model = $this->paymentFactory->create();
        $this->paymentPopulator
            ->populateFromCreateForm($model, $form);

        $this->paymentRepository->save($model);

        return $model;
    }

    /**
    * @param Payment $model
    * @param PaymentUpdateForm $form
    * @return Payment
    * @throws UnSuccessModelException
    */
    public function updateByForm(Payment $model, PaymentUpdateForm $form): Payment
    {
        $this->paymentPopulator
            ->populateFromUpdateForm($model, $form);

        $this->paymentRepository->save($model);

        return $model;
    }

    /**
    * @param $id
    * @return Payment|array|ActiveRecord
    * @throws PaymentNotFoundException
    */
    public function getPayment($id)
    {
        return $this->paymentRepository->getPaymentById($id);
    }

    public function generatePaymentData(array $advances): void
    {
        foreach ($advances as $advance) {
            /** @var Advance $advance */
            $model = $this->paymentRepository
                ->getPaymentByDateAndAdvance(DateHelper::nowWithoutHours(), $advance->id);

            if ($model) {
                continue;
            }

            $model = $this->paymentFactory->create();

            $this->paymentPopulator
                ->populateFromAdvance($model, $advance);

            $this->paymentRepository->savePayment($model);
            $advance->updateCounters(['payment_left' => -1]);
        }
    }

    public function getGroupedPayments(string $date, User $user): PaymentCollection
    {
        $payments = $this->paymentRepository
            ->getNeedPays($date, $user->isSuperadmin ? null : $user->id);

        return new PaymentCollection($payments);
    }

    public function getGroupedClosedPayments(string $date, User $user): PaymentCollection
    {
        $payments = $this->paymentRepository
            ->getPayd($date, $user->isSuperadmin ? null : $user->id);

        return new PaymentCollection($payments);
    }

    public function payByPayment(Payment $paymentModel, int $amount)
    {
        $paymentModel->amount -= $amount;
        $this->paymentRepository->save($paymentModel);

        $this->advanceService->calculatePayLeftSumm($paymentModel->advance, $amount);
    }

    public function payDebtsFromClientBalance(array $advances): void
    {
        foreach ($advances as $advance) {
            if ($advance->client->getAllDebts()) {
                $form = ClientPayForm::loadAndValidate([
                    'advance_ids' => array_column($advance->client->lastDebtPayments, 'advance_id'),
                    'amount' => $advance->client->balance,
                    'in_cart' => null
                ]);
                $payDto = new PayDto($advance->user, $advance->client, $form);

                $startHandler = new StartHandler();
                $debtHandler = new DebtHandler();
                $balanceHandler = new BalanceUpdateHandler();

                if($payDto->amount==0){
                    $emptyHandler = new EmptyHandler();
                    $startHandler
                        ->setNext($emptyHandler);

                    $startHandler->handle(true, $payDto);
                    continue;
                }
                $startHandler
                    ->setNext($debtHandler)
                    ->setNext($balanceHandler);

                $startHandler->handle(true, $payDto);
            }
        }
    }

    /* 
     * получить все платежи кроме сегодняшнего на которых есть долги
    */
    public function getLastDebtPayments(User $user)
    {
        $payments = $this->paymentRepository
            ->getLastDebtPayments($user->isSuperadmin ? null : $user->id);

        return PaymentSerializerWithShortClient::serialize($payments);
    }
}