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
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceNoteForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use Exception;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

class AdvanceService extends BaseService
{

    protected AdvanceFactory $advanceFactory;

    protected AdvanceRepository $advanceRepository;

    protected AdvancePopulator $advancePopulator;

    public function injectDependencies(AdvanceFactory $advanceFactory, AdvanceRepository $advanceRepository, AdvancePopulator $advancePopulator): void
    {
        $this->advanceFactory = $advanceFactory;
        $this->advanceRepository = $advanceRepository;
        $this->advancePopulator = $advancePopulator;
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
        $this->advancePopulator
            ->populateFromCreateForm($model, $form)
            ->populateClient($model, $client)
            ->populateUser($model, $user);

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
            throw new AdvanceStatusException('Обобрить заявку можно только в статусе "Отправлено"');
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

    public function getAdvanceDto(int $advanceId): AdvanceDto
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        return (new AdvanceCalculator())->calculate(
            $model->amount,
            $model->limitation,
            $model->daily_payment
        );
    }

    /**
     * Выдача займа c загрузкой расписки
     * @param int $advanceId
     * @param AdvanceNoteForm $form
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws Exception
     */
    public function issueAdvance(int $advanceId, AdvanceNoteForm $form): void
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if(!$model->isApproved()) {
            throw new AdvanceStatusException('Разрешена выдача только одобренных займов');
        }

        $this->advancePopulator
            ->populateNote($model, UploadedFile::getInstanceByName('note'))
            ->changeStatus($model, Advance::STATE_ISSUED);
        $this->advanceRepository->saveAdvanceNote($model);
    }

    /**
     * @param Advance $model
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function deleteAdvance(Advance $model): void
    {
        $model->delete();
    }
}