<?php

namespace app\modules\advance\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\UserException;
use app\models\Advance;
use app\models\Client;
use app\models\User;
use app\modules\advance\dto\AdvanceDto;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\forms\AdvanceApprovedForm;
use app\modules\advance\forms\AdvanceCreateByClientForm;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use app\modules\client\components\ClientRepository;
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

    public function injectDependencies(
        AdvanceFactory $advanceFactory,
        AdvanceRepository $advanceRepository,
        AdvancePopulator $advancePopulator,
        UserManager $userManager,
        ClientRepository $clientRepository
    ): void
    {
        $this->advanceFactory = $advanceFactory;
        $this->advanceRepository = $advanceRepository;
        $this->clientRepository = $clientRepository;
        $this->advancePopulator = $advancePopulator;
        $this->userManager = $userManager;
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

        return  $currentUser->isSuperadmin ? 'Заявка одобрена' : 'Заявка отправлена';
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
}