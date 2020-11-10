<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\helpers\DateHelper;
use app\models\Advance;
use app\models\Payment;
use app\models\User;
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

    public function injectDependencies(PaymentFactory $paymentFactory, PaymentRepository $paymentRepository, PaymentPopulator $paymentPopulator): void
    {
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
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
}