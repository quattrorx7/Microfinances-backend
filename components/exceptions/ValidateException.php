<?php

namespace app\components\exceptions;

use yii\base\Model;

class ValidateException extends UnSuccessModelException
{
    public function __construct(Model $model, $message = 'Валидация данных провалена')
    {
        parent::__construct($message, $model);
    }
}
