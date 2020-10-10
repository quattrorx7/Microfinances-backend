<?php

namespace app\components\filters;

use yii\web\UnauthorizedHttpException;

class HttpHeaderAuth extends \yii\filters\auth\HttpHeaderAuth
{
    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException(\Yii::t('yii', 'Your request was made with invalid credentials.'));
    }
}