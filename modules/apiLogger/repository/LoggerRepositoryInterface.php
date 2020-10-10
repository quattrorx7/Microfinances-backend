<?php

namespace app\modules\apiLogger\repository;

use app\modules\apiLogger\models\LoggerModelInterface;

interface LoggerRepositoryInterface
{
    public function save(LoggerModelInterface $model): void ;
}