<?php

namespace app\modules\files\services;

use app\models\File;
use app\modules\files\factory\FilesFactory;
use app\modules\files\repository\FilesRepository;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\web\UploadedFile;

class FilesService extends BaseObject
{

    protected FilesFactory $filesFactory;

    protected FilesRepository $filesRepository;

    public function init()
    {
        parent::init();

        $this->filesFactory = Yii::createObject(FilesFactory::class);
        $this->filesRepository = Yii::createObject(FilesRepository::class);
    }

    /**
     * @param UploadedFile $file
     * @return File
     * @throws Exception
     */
    public function saveUploadedFile(UploadedFile $file): File
    {
        $model = $this->filesFactory->createFromUploadedFile($file);
        return $this->filesRepository->saveFile($model);
    }

    /**
     * @param UploadedFile[]
     * @return File[]
     * @throws Exception
     */
    public function saveUploadedFiles($files): array
    {
        $res = [];
        $models = $this->filesFactory->createFromUploadedFiles($files);

        foreach ($models as $model) {
            $res[] = $this->filesRepository->saveFile($model);
        }

        return $res;
    }

    public function getFileContent($file)
    {
        return file_get_contents($file->full_path);
    }
}