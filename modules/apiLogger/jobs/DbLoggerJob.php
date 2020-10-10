<?php

namespace app\modules\apiLogger\jobs;

use app\modules\apiLogger\models\DbLoggerModel;
use app\modules\apiLogger\repository\DbLoggerRepository;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class DbLoggerJob extends BaseObject implements JobInterface
{

    public DbLoggerModel $model;

    protected DbLoggerRepository $dbLoggerRepository;

    public function init()
    {
        parent::init();
        $this->dbLoggerRepository = Yii::createObject(DbLoggerRepository::class);
    }

    public function execute($queue)
    {
        $this->dbLoggerRepository->save($this->model);
    }
}