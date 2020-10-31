<?php

namespace app\modules\advance\components;

use app\components\populator\AbstractPopulator;
use app\models\Advance;
use app\models\Client;
use app\models\User;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use app\modules\files\services\FilesService;
use yii\web\UploadedFile;

class AdvancePopulator extends AbstractPopulator
{

    public function populateFromCreateWithClientForm(Advance $model, AdvanceCreateWithClientForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'created_at',
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


    public function populateFromCreateForm(Advance $model, AdvanceCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'created_at',
            'limitation'
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