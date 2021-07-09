<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\helpers\DateHelper;
use app\models\Advance;
use app\models\Payment;
use app\models\PaymentHistory;
use app\models\User;
use app\modules\advance\components\AdvanceService;
use app\modules\api\serializer\payment\PaymentSerializer;
use app\modules\api\serializer\payment\PaymentSerializerWithShortClient;
use app\modules\client\components\ClientService;
use app\modules\client\dto\PayDto;
use app\modules\client\forms\ClientPayForm;
use app\modules\client\handlers\BalanceUpdateHandler;
use app\modules\client\handlers\DebtHandler;
use app\modules\client\handlers\EmptyHandler;
use app\modules\client\handlers\StartHandler;
use app\modules\client\helpers\ClientPayHelper;
use app\modules\payment\dto\PaymentCollection;
use app\modules\payment\exceptions\PaymentNotFoundException;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentUpdateForm;
use yii\db\ActiveRecord;
use DateTime;

class PaymentService extends BaseService
{

    protected PaymentFactory $paymentFactory;

    protected PaymentRepository $paymentRepository;

    protected PaymentPopulator $paymentPopulator;

    protected PaymentHistoryService $paymentHistoryService;

    protected AdvanceService $advanceService;

    protected ClientService $clientService;

    public function injectDependencies(
        PaymentFactory $paymentFactory,
        PaymentRepository $paymentRepository,
        PaymentPopulator $paymentPopulator,
        PaymentHistoryService $paymentHistoryService,
        AdvanceService $advanceService,
        ClientService $clientService
    ): void
    {
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
        $this->paymentHistoryService = $paymentHistoryService;
        $this->advanceService = $advanceService;
        $this->clientService = $clientService;
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

    public function generatePaymentDataToDay(Advance $advance): void
    {
        $start = new DateTime($advance->issue_date);
        $start->modify('-1 day');
        $now = (new DateTime(DateHelper::nowWithoutHours()))->format('Y-m-d');
        
        $payment_left = $advance->payment_left;
        for($i = 0; $i< $payment_left; $i++){
            $date = $start->modify('+1 day');
           
            $model = $this->paymentFactory->createByDate($date);

            $this->paymentPopulator
                ->populateFromAdvance($model, $advance);

            $this->paymentRepository->savePayment($model);
            $advance->updateCounters(['payment_left' => -1]);
            
            if($now == $date->format('Y-m-d')){
                break;
            }
        }

    }

    public function generatePayment(Advance $advance, $data): void
    {
        /** @var Advance $advance */
        $model = $this->paymentRepository
            ->getPaymentByDateAndAdvance($data, $advance->id);

        if ($model) {
            throw new \yii\Web\HttpException(420, 'Платеж на дату '.$data.' уже создан');
        }

        $date_pay = new DateTime($data);
        $model = $this->paymentFactory->createByDate($date_pay);

        $this->paymentPopulator
            ->populateFromAdvance($model, $advance);

        $this->paymentRepository->savePayment($model);
        $advance->updateCounters(['payment_left' => -1]);
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

    public function getPayments(string $date, ?int $userId)
    {
        $payments = $this->paymentRepository
            ->getNeedPays($date, $userId);

        return PaymentSerializer::serialize($payments);
    }

    public function getTodayPaymentCount(string $date, ?int $userId)
    {
        $payments = $this->paymentRepository
            ->getTodayPaymentCount($date, $userId);

        $arr = ['cash'=>0, 'card'=>0];
        
        foreach($payments as $value){
            if($value['type']==PaymentHistory::PAYMENT_TYPE_CASH || $value['type']==PaymentHistory::PAYMENT_TYPE_CASH_BALANCE){
                $arr['cash'] = $value['amount'];
            }
            if($value['type']==PaymentHistory::PAYMENT_TYPE_CARD || $value['type']==PaymentHistory::PAYMENT_TYPE_CARD_BALANCE){
                $arr['card'] = $value['amount'];
            }
        }

        return $arr;
    }

    public function returnPayment(int $paymentId)
    {
        $payment = $this->paymentRepository->getPaymentById($paymentId);

        $pays = $this->paymentHistoryService->getHistoryByClientIdAndDate($payment->client_id, DateHelper::nowWithoutHours());

        $summa = 0;
        $balance = 0;
        foreach($pays as $pay){
            if($pay->type == PaymentHistory::PAYMENT_TYPE_CARD || $pay->type == PaymentHistory::PAYMENT_TYPE_CASH){
                $summa += $pay->amount;
                if($pay->advance->isClosed()){
                    $pay->advance->payment_status = Advance::PAYMENT_STATUS_STARTED;
                    $pay->advance->save();
                }
                $pay->advance->updateCounters(['summa_left_to_pay' => $pay->amount]);
                $pay->payment->updateCounters(['amount' => $pay->amount]);
                $pay->delete();
            }else if($pay->type == PaymentHistory::PAYMENT_TYPE_CARD_BALANCE || $pay->type == PaymentHistory::PAYMENT_TYPE_CASH_BALANCE){
                $balance += $pay->amount;
                $pay->delete();
            }else if($pay->type == PaymentHistory::PAYMENT_TYPE_BALANCE){
                $balance -= $pay->amount;
                if($pay->advance->isClosed()){
                    $pay->advance->payment_status = Advance::PAYMENT_STATUS_STARTED;
                    $pay->advance->save();
                }
                $pay->advance->updateCounters(['summa_left_to_pay' => $pay->amount]);
                $pay->payment->updateCounters(['amount' => $pay->amount]);
                $pay->delete();
            }
        }

        $client = $payment->client;
        $client->updateCounters(['balance' => -1*$balance]);

        return [
            'mes'=>'Успешный возврат платежа',
            'data'=>[
                'payments'=>$summa,
                'balance'=>$balance,
            ]
        ];
    }


    /**
     * Оплата из админ панели
     */
    public function payFromAdminPanel(Payment $paymentModel, int $amount, $date_pay, $inCart)
    {
        $payAmount = ClientPayHelper::differenceResult($amount, $paymentModel->amount);
        
        //Основной платеж
        if($payAmount>0){
            $paymentModel->amount -= $payAmount;
            $this->paymentRepository->save($paymentModel);

            $this->advanceService->calculatePayLeftSumm($paymentModel->advance, $payAmount);

            $type = $inCart ? PaymentHistory::PAYMENT_TYPE_CARD : PaymentHistory::PAYMENT_TYPE_CASH;

            (new PaymentHistoryService())->saveHistory($paymentModel->user, $paymentModel->client, $paymentModel, $payAmount, $inCart, 'payment', $type, $date_pay);
        } 
        //Оставшаяся часть на баланс
        if($amount>$payAmount) {
            $amount -= $payAmount;
            $type = $inCart ? PaymentHistory::PAYMENT_TYPE_CARD_BALANCE : PaymentHistory::PAYMENT_TYPE_CASH_BALANCE;
            
            (new PaymentHistoryService())->saveHistory($paymentModel->user, $paymentModel->client, $paymentModel, $amount, $inCart, 'payment', $type, $date_pay);
            $this->clientService->updateBalance($paymentModel->client, $amount);
        }

    }

    /**
     * Долг из админ панели
     */
    public function debtFromAdminPanel(Payment $paymentModel, $date_pay)
    {
        (new PaymentHistoryService())->saveHistory($paymentModel->user, $paymentModel->client, $paymentModel, 0, null, 'payment', PaymentHistory::PAYMENT_TYPE_AUTO, $date_pay);

    }
    
}