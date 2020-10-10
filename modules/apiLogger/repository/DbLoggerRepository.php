<?php

namespace app\modules\apiLogger\repository;

use app\modules\apiLogger\models\DbLoggerModel;
use app\modules\apiLogger\models\LoggerModelInterface;

class DbLoggerRepository implements LoggerRepositoryInterface
{
    /**
     * @param LoggerModelInterface $fileLoggerModel
     */
    public function save(LoggerModelInterface $fileLoggerModel): void
    {
        $fileLoggerModel->save();
    }

    public function getById($id)
    {
        return DbLoggerModel::find()
            ->where(['id' => $id])
            ->one();
    }
}