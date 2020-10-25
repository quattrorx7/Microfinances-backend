<?php

namespace app\modules\api\validators;

use yii\validators\Validator;

class PhoneValidator extends Validator
{

    public function validateAttribute($model, $attribute): void
    {
        $model->$attribute = preg_replace('/[\D]/', '', $model->$attribute);

        if (!preg_match('/^\d{11}$/', $model->$attribute)) {
            $this->addError($model, $attribute, 'Номер телефона должен содержать 11 цифр');
        }
    }

}