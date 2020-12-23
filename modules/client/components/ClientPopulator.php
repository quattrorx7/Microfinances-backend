<?php

namespace app\modules\client\components;

use app\components\populator\AbstractPopulator;
use app\models\Client;
use app\models\District;
use app\modules\client\forms\ClientCreateForm;
use app\modules\client\forms\ClientUpdateForm;
use app\modules\files\services\FilesService;
use yii\web\UploadedFile;

class ClientPopulator extends AbstractPopulator
{

    public function populateFromCreateForm(Client $model, ClientCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'name',
            'surname',
            'patronymic',
            'phone',
            'additional_phone',
            'residence_address',
            'work_address',
            'activity',
            'profit',
            'comment'
        ]);

        return $this;
    }

    public function populateDistrict(Client $model, District $district = null): self
    {
        if ($district instanceof District) {
            $model->populateRelation('district', $district);
        }
        return $this;
    }

    public function populateFromUpdateForm(Client $model, ClientUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'name',
            'surname',
            'patronymic',
            'phone',
            'additional_phone',
            'residence_address',
            'work_address',
        ]);

        return $this;
    }

    public function populateFiles(Client $model, $uploadedFiles = []): self
    {
        $fileService = \Yii::createObject(FilesService::class);
        $files = [];

        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                $files[] = $fileService->saveUploadedFile($uploadedFile);
            }
        }

        if ($files) {
            $model->setFile($files);
        }

        return $this;
    }

    public function getModel(Client $model)
    {
        return $model;
    }
}