<?php

namespace app\modules\apiLogger\services;

use yii\web\Request;

interface ApiLoggerServiceInterface
{
    public function exportRequest(Request $request);

    public function exportResponse($response, ?int $userId);
}