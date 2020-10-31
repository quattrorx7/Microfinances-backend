<?php

namespace app\modules\advance\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\Advance;
use app\models\Client;
use app\models\User;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceNoteForm;
use app\modules\advance\forms\AdvanceStatusForm;
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
     * Загрузка расписки
     * @param int $advanceId
     * @param AdvanceNoteForm $form
     * @return Advance|array|\yii\db\ActiveRecord
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws Exception
     */
    public function loadNote(int $advanceId, AdvanceNoteForm $form)
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if(!$model->isApproved()) {
            throw new AdvanceStatusException('Загрузка расписки возможна только в одобренные заявки');
        }

        $this->advancePopulator
            ->populateNote($model, UploadedFile::getInstanceByName('note'));
        $this->advanceRepository->saveAdvanceNote($model);

        return $model;
    }

    /**
     * Выдача займа (смена статуса)
     * @param int $advanceId
     * @return Advance|array|\yii\db\ActiveRecord
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     */
    public function issueAdvance(int $advanceId, AdvanceStatusForm $form): string
    {
        $model = $this->advanceRepository->getAdvanceById($advanceId);

        if(!$model->isApproved() || !$model->hasNote()) {
            throw new AdvanceStatusException('Разрешена выдача только одобренных займов в распиской');
        }

        $this->advancePopulator->changeStatus($model, $form->status);
        $this->advanceRepository->save($model);

        return $model->isDenied() ? 'Отказано' : 'Займ выдан';
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