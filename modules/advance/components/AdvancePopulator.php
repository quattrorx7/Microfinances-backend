<?php

namespace app\modules\advance\components;

use app\components\populator\AbstractPopulator;
use app\helpers\DateHelper;
use app\models\Advance;
use app\models\Client;
use app\models\User;
use app\modules\advance\dto\AdvanceDto;
use app\modules\advance\forms\AdvanceApprovedForm;
use app\modules\advance\forms\AdvanceCreateByClientForm;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use app\modules\advance\forms\RefinancingForm;
use app\modules\files\services\FilesService;
use yii\web\UploadedFile;

class AdvancePopulator extends AbstractPopulator
{

    public function populateFromCreateWithClientForm(Advance $model, AdvanceCreateWithClientForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'issue_date',
            'limitation'
        ]);

        return $this;
    }

    public function populateClient(Advance $model, Client $client): self
    {
        $model->populateRelation('client', $client);
        return $this;
    }


    public function populateUser(Advance $model, User $user): self
    {
        $model->populateRelation('user', $user);
        return $this;
    }

    public function populateRefinancing(Advance $model, Advance $ref): self
    {
        $model->populateRelation('refinancing', $ref);
        return $this;
    }

    public function populateFromCreateForm(Advance $model, AdvanceCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'issue_date',
            'limitation',
            'daily_payment'
        ]);

        return $this;
    }

    public function populateFromRefinancingForm(Advance $model, RefinancingForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'limitation',
        ]);

        $model->refinancing_ids = json_encode($form->advance_ids);

        return $this;
    }

    public function populateFromCreateByClientForm(Advance $model, AdvanceCreateByClientForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'issue_date',
            'limitation',
            'daily_payment'
        ]);

        return $this;
    }

    public function populateFromApprovedForm(Advance $model, AdvanceApprovedForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'issue_date',
            'limitation',
            'user_id',
            'daily_payment'
        ]);

        return $this;
    }

    public function populateFromApprovedRefinancingForm(Advance $model, RefinancingForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'limitation',
            'daily_payment',
            'issue_date'
        ]);

        // $issue_date = DateHelper::getModifyDate(DateHelper::now(), '+1 day');
        // $model->issue_date = DateHelper::formatDate($issue_date, 'Y-m-d H:i:s');

        return $this;
    }

    public function populateFromCalculateDto(Advance $model, AdvanceDto $dto): self
    {
        $this->populateAttributes($model, $dto->getAttributes(), [
            'summa_with_percent',
            'percent'
        ]);

        return $this;
    }

    public function populateNote(Advance $model, UploadedFile $uploadedFile)
    {
        $fileService = \Yii::createObject(FilesService::class);
        $file = $fileService->saveUploadedFile($uploadedFile);

        if ($file) {
            $model->setNote($file);
        }

        return $this;
    }

    public function changeStatus(Advance $model, string $status)
    {
        $this->populateAttributes($model, ['status' => $status], [
            'status'
        ]);

        return $this;
    }

    public function populateFromUpdateForm(Advance $model, AdvanceUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

}