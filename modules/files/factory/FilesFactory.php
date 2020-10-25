<?php

namespace app\modules\files\factory;

use app\models\File;
use app\modules\files\components\FileManager;
use app\modules\files\helpers\FilesHelper;
use yii\base\Exception;
use yii\web\UploadedFile;

class FilesFactory
{
    /**
     * @param UploadedFile $file
     * @return File
     * @throws Exception
     */
    public function createFromUploadedFile(UploadedFile $file): File
    {
        $model = new File;
        $model->hash = FilesHelper::getHash($file->tempName);
        $model->path = FilesHelper::setPath($model->hash);
        $model->title = str_replace(['/', '\\'], '', $model->title);
        $model->filename = FilesHelper::setFileName($model->title) . $file->extension;

        $manager = FileManager::create($model->getPath(), $file->tempName);
        $model->size = $manager->getFileSize();
        $model->mimetype = $manager->getMimeType();

        return $model;
    }

    /**
     * @param array $files
     * @return array
     * @throws Exception
     */
    public function createFromUploadedFiles(array $files): array
    {
        $models = [];

        foreach ($files as $file) {
            $models[] = $this->createFromUploadedFile($file);
        }

        return $models;
    }
}