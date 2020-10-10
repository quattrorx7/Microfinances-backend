<?php

namespace app\modules\apiLogger\jobs;

use app\modules\apiLogger\models\FileLoggerModel;
use app\modules\apiLogger\repository\FileLoggerRepository;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\queue\JobInterface;
use yii\queue\Queue;

class FileLoggerJob extends BaseObject implements JobInterface
{

    public FileLoggerModel $model;

    protected FileLoggerRepository $fileLoggerRepository;

    public function init()
    {
        parent::init();
        $this->fileLoggerRepository = Yii::createObject(FileLoggerRepository::class);
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws Exception
     */
    public function execute($queue)
    {
        $this->fileLoggerRepository->save($this->model);
    }
}