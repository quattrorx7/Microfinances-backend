<?php

namespace app\modules\advance\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\UserException;
use app\helpers\DateHelper;
use app\models\Advance;
use app\models\Client;
use app\models\Payment;
use app\models\PaymentHistory;
use app\models\User;
use app\modules\advance\dto\AdvanceCloseDto;
use app\modules\advance\dto\AdvanceDto;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\forms\AdvanceApprovedForm;
use app\modules\advance\forms\AdvanceCreateByClientForm;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use app\modules\advance\forms\CloseForm;
use app\modules\advance\forms\RefinancingForm;
use app\modules\client\components\ClientRepository;
use app\modules\payment\components\PaymentHistoryService;
use app\modules\payment\components\PaymentRepository;
use app\modules\user\components\NotificationService;
use app\modules\user\components\UserManager;
use Exception;
use yii\web\UploadedFile;

class AdvanceService extends BaseService
{

    protected AdvanceFactory $advanceFactory;

    protected AdvanceRepository $advanceRepository;

    protected ClientRepository $clientRepository;

    protected AdvancePopulator $advancePopulator;

    protected UserManager $userManager;

    protected PaymentRepository $paymentRepository;

    protected NotificationService $notificationService;

    public function injectDependencies(
        AdvanceFactory $advanceFactory,
        AdvanceRepository $advanceRepository,
        AdvancePopulator $advancePopulator,
        UserManager $userManager,
        ClientRepository $clientRepository,
        PaymentRepository $paymentRepository,
        NotificationService $notificationService
    ): void
    {
        $this->advanceFactory = $advanceFactory;
        $this->advanceRepository = $advanceRepository;
        $this->clientRepository = $clientRepository;
        $this->advancePopulator = $advancePopulator;
        $this->userManager = $userManager;
        $this->paymentRepository = $paymentRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param AdvanceCreateForm $form
     * @param User $user
     * @param Client $client
     * @param bool $isAdmin
     * @return Advance
     * @throws Exception
     */
    public function createByForm(AdvanceCreateForm $form, User $user, Client $client, $isAdmin = false): Advance
    {
        $model = $isAdmin ? $this->advanceFactory->createWithAdmin() : $this->advanceFactory->create();

        $calculateDto = (new AdvanceCalculator())->calculate(
            $form->amount,
            $form->limitation,
            $form->daily_payment
        );

        $this->advancePopulator
            ->populateFromCreateForm($model, $form)
            ->populateClient($model, $client)
            ->populateUser($model, $user)
            ->populateFromCalculateDto($model, $calculateDto);

        if ($isAdmin) {
            $this->calculate($form->amount, $form->limitation, $form->daily_payment);
        }

        $this->advanceRepository->saveAdvance($model);

        $this->notificationService->sendToAdmin('Новая заявка с клиентов', 'Новая заявка с клиентов', ['advanceId'=>$model->id]);

        return $model;
    }

    public function createForClient(AdvanceCreateWithClientForm $form, User $currentUser, Client $client): Advance
    {
        $model = $this->advanceFactory->create();
        $this->advancePopulator
            ->populateFromCreateWithClientForm($model, $form)
            ->populateClient($model, $client)
            ->populateUser($model, $currentUser);

        $this->advanceRepository->saveAdvance($model);

        $this->notificationService->sendToUser($model->user, 'Одобренная заявка', 'Одобренная заявка', ['advanceId'=>$model->id]);

        return $model;
    }

    /**
    * @param Advance $model
    * @param AdvanceUpdateForm $form
    * @return Advance
    * @throws UnSuccessModelException
    */
    public function updateByForm(Advance $model, AdvanceUpdateForm $form): Advance
    {
        $this->advancePopulator
            ->populateFromUpdateForm($model, $form);

        $this->advanceRepository->save($model);

        return $model;
    }

    /**
    * @param $id
    * @return Advance|array|\yii\db\ActiveRecord
    * @throws AdvanceNotFoundException
    */
    public function getAdvance($id)
    {
        return $this->advanceRepository->getAdvanceById($id);
    }

    public function searchLast()
    {
        return $this->advanceRepository->getLastAdvances();
    }

    /**
     * Отклонить заявку
     * @param int $advanceId
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     */
    public function deniedAdvance(int $advanceId): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if (!$model->isSent()) {
            throw new AdvanceStatusException('Отклонить заявку можно только в статусе "Отправлено"');
        }

        $this->advancePopulator->changeStatus($model, Advance::STATE_DENIED);
        $this->advanceRepository->save($model);

        $this->notificationService->sendToUser($model->user, 'Заявка отклонена', 'Заявка отклонена', ['advanceId'=>$model->id]);
    }

    /**
     * @param int $advanceId
     * @param AdvanceApprovedForm $form
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException|UserException
     */
    public function approvedAdvance(int $advanceId, AdvanceApprovedForm $form): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if (!$model->isSent()) {
            throw new AdvanceStatusException('Одобрить заявку можно только в статусе "Отправлено"');
        }

        $calculateDto = (new AdvanceCalculator())->calculate(
            $form->amount,
            $form->limitation,
            $form->daily_payment
        );

        $this->advancePopulator
            ->populateFromApprovedForm($model, $form)
            ->populateFromCalculateDto($model, $calculateDto)
            ->changeStatus($model, Advance::STATE_APPROVED);

        $this->advanceRepository->save($model);

        $this->notificationService->sendToUser($model->user, 'Одобренная заявка', 'Одобренная заявка', ['advanceId'=>$model->id]);
    }

    public function calculate(int $amount, int $limitation, int $daily_payment): AdvanceDto
    {
        return (new AdvanceCalculator())->calculate(
            $amount,
            $limitation,
            $daily_payment
        );
    }

    /**
     * Выдача займа c загрузкой расписки
     * @param int $advanceId
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws Exception
     */
    public function issueAdvance(int $advanceId): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if(!$model->isApproved()) {
            throw new AdvanceStatusException('Разрешена выдача только одобренных займов');
        }

        $this->advancePopulator
            ->populateNote($model, UploadedFile::getInstanceByName('note'))
            ->changeStatus($model, Advance::STATE_ISSUED);

        $model->activatePaymentProcess();

        $this->advanceRepository->saveAdvanceNote($model);
    }

    public function createByClientForm(AdvanceCreateByClientForm $form, User $currentUser): string
    {
        $client = $this->clientRepository->getClientById($form->client_id);

        $model = $currentUser->isSuperadmin ? $this->advanceFactory->createWithAdmin() : $this->advanceFactory->create();
        $user = $currentUser->isSuperadmin ? $this->userManager->getUserById($form->user_id) : $currentUser;
        $form->daily_payment = $currentUser->isSuperadmin ? $form->daily_payment : null;

        $this->advancePopulator
            ->populateFromCreateByClientForm($model, $form)
            ->populateClient($model, $client)
            ->populateUser($model, $user);

        if ($currentUser->isSuperadmin) {
            $calculateDto = $this->calculate($form->amount, $form->limitation, $form->daily_payment);

            $this->advancePopulator
                ->populateFromCalculateDto($model, $calculateDto);
        }

        $this->advanceRepository->saveAdvance($model);

        if($currentUser->isSuperadmin){
            if($client->owner_id != $model->user->id){
                Advance::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
                Payment::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
                $client->owner_id = $user->id;
                $client->save();
            }
            
            $this->notificationService->sendToUser($user, 'Одобренная заявка', 'Одобренная заявка', ['advanceId'=>$model->id]);
        }else{
            $this->notificationService->sendToAdmin('Новая заявка', 'Новая заявка', ['advanceId'=>$model->id]);
        }

        return  $currentUser->isSuperadmin ? 'Заявка одобрена' : 'Заявка отправлена';
    }

    public function createRefinancingByClientForm(RefinancingForm $form, array $advances, Client $client, User $currentUser): string
    {
        $model = $currentUser->isSuperadmin ? $this->advanceFactory->createWithAdmin() : $this->advanceFactory->create();
        $user = $currentUser->isSuperadmin ? $this->userManager->getUserById($form->user_id) : $currentUser;
        $form->daily_payment = $currentUser->isSuperadmin ? $form->daily_payment : null;

        $model->refinancing = 1;
        
        $this->advancePopulator
            ->populateFromRefinancingForm($model, $form)
            ->populateClient($model, $client)
            ->populateUser($model, $user);

        if ($currentUser->isSuperadmin) {
            $calculateDto = $this->calculate($form->amount, $form->limitation, $form->daily_payment);

            $this->advancePopulator
            ->populateFromApprovedRefinancingForm($model, $form)
                ->populateFromCalculateDto($model, $calculateDto)
                ->changeStatus($model, Advance::STATE_APPROVED);

        }

        $this->advanceRepository->saveAdvance($model);

        if($currentUser->isSuperadmin){
            if($client->owner_id != $model->user->id){
                Advance::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
                Payment::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
                $client->owner_id = $user->id;
                $client->save();
            }
            $this->notificationService->sendToUser($user, 'Одобренная заявка', 'Одобренная заявка', ['advanceId'=>$model->id]);
        }else{
            $this->notificationService->sendToAdmin('Новая заявка', 'Новая заявка', ['advanceId'=>$model->id]);
        }

        return  $currentUser->isSuperadmin ? 'Заявка одобрена' : 'Заявка отправлена';
    }

    /**
     * @param int $advanceId
     * @param AdvanceApprovedForm $form
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException|UserException
     */
    public function approvedRefinancing(int $advanceId, RefinancingForm $form): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);
        $user = $this->userManager->getUserById($form->user_id);
        $client = $model->client;
        if (!$model->isSent()) {
            throw new AdvanceStatusException('Одобрить заявку можно только в статусе "Отправлено"');
        }

        $calculateDto = (new AdvanceCalculator())->calculate(
            $form->amount,
            $form->limitation,
            $form->daily_payment
        );

        $this->advancePopulator
            ->populateFromApprovedRefinancingForm($model, $form)
            ->populateFromCalculateDto($model, $calculateDto)
            ->changeStatus($model, Advance::STATE_APPROVED)
            ->populateUser($model, $user);


        $this->advanceRepository->save($model);

        if($client->owner_id != $user->id){
            Advance::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
            Payment::updateAll(['user_id'=>$model->user_id], ['client_id'=>$model->client_id]);
            $client->owner_id = $user->id;
            $client->save();
        }

        $this->notificationService->sendToUser($user, 'Одобренная заявка', 'Одобренная заявка', ['advanceId'=>$model->id]);
    }

    /**
     * Выдача рефинансирования c загрузкой расписки
     * @param int $advanceId
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws Exception
     */
    public function issueRefinancing(int $advanceId): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if(!$model->isApproved()) {
            throw new AdvanceStatusException('Разрешена выдача только одобренных займов');
        }

        $this->advancePopulator
            ->populateNote($model, UploadedFile::getInstanceByName('note'))
            ->changeStatus($model, Advance::STATE_ISSUED);

        $model->activatePaymentProcess();

        $this->advanceRepository->saveAdvanceNote($model);

        $ids = $model->refinancingIds();

        $refs = $this->advanceRepository->getRefinancingById($ids);

        foreach($refs as $ref){
            $ref->payment_status = Advance::PAYMENT_STATUS_CLOSED;
            $ref->payment_left = 0;
            $ref->save();
        }

        Payment::updateAll(['amount'=>0], ['AND', ['in', 'advance_id', $ids], ['>', 'amount', 0]]);

    }

    /**
     * Досрочное закрытие Расчет процентов
     */
    public function closePercent(CloseForm $form, $advances, $client, $user)
    {
        $dto = new AdvanceCloseDto($advances);
        return $dto->getAttributes();
    }

    /**
     * Досрочное закрытие займов
     */
    public function close(CloseForm $form, $advances, $client, $user)
    {
        $dto = new AdvanceCloseDto($advances);
        $date = DateHelper::formatDate(DateHelper::now(), 'Y-m-d');

        $paymentArr = $dto->getPayment();
        $ids = [];
        foreach($advances as $key=>$advance){
            $advance->payment_status = Advance::PAYMENT_STATUS_CLOSED;
            $advance->payment_left = 0;
            $advance->summa_left_to_pay = 0;
            $advance->save();

            $payment = $this->paymentRepository->getPaymentByDateAndAdvance($date, $advance->id);
            if($payment==null){
                $payment = $this->paymentRepository->getPaymentLastAndAdvance($advance->id);
            }
       
            $type = $form->in_cart ? PaymentHistory::PAYMENT_TYPE_CARD : PaymentHistory::PAYMENT_TYPE_CASH;

            (new PaymentHistoryService())->saveHistory($user, $client, $payment, $paymentArr[$key], $form->in_cart, 'closed', $type);
            $ids[] = $advance->id;
        }

        Payment::updateAll(['amount'=>0], ['AND', ['in', 'advance_id', $ids], ['>', 'amount', 0]]);

        return "Займы погашены";
    }

    public function getActiveAdvancesByDate(string $date)
    {
        return $this->advanceRepository->getActiveAdvances($date);
    }

    public function getDebtPayments(string $date)
    {
        return $this->advanceRepository->getDebtAdvances($date);
    }

    public function calculatePayLeftSumm(Advance $advance, int $amount)
    {
        $advance->summa_left_to_pay -= $amount;

        if ($advance->summa_left_to_pay === 0) {
            $advance->payment_status = Advance::PAYMENT_STATUS_CLOSED;
        }

        $this->advanceRepository->save($advance);
        /** @todo return events */
    }

    /**
     * История займов
     */
    public function getHistoryByClientId(int $clientId): array
    {
        $list = $this->advanceRepository->getHistoryByClientId($clientId);

        $list = array_filter($list, function($item){
            if($item->status == Advance::STATE_ISSUED && $item->payment_status == Advance::PAYMENT_STATUS_NULL){
                return false;
            }
            return true;
        });

        return $list;
    }

    public function getHistoryAppByUserId(int $userId)
    {
        $list = $this->advanceRepository->getHistoryAppByUserId($userId);

        return $list;
    }

    public function getToday(string $date, int $userId = null)
    {
        $query = $this->advanceRepository->getNewQuery($date, $userId);

        return $query->all();
    }

    public function getTodayCount(string $date, int $userId = null)
    {
        $query = $this->advanceRepository->getNewQuery($date, $userId);

        return $query->count();
    }

    public function statisticIssuedAdvance($from, $to)
    {
        $query = $this->advanceRepository->getStatistic($from, $to);

        return (int)$query->sum('summa_with_percent')??0;
    }

    public function statisticRefinancingdAdvance($from, $to)
    {
        $query = $this->advanceRepository->getStatisticRefinancing($from, $to);

        return (int)$query->sum('summa_with_percent')??0;
    }
}