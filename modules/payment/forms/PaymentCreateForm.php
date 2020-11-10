<?php

namespace app\modules\payment\forms;

use app\modules\payment\exceptions\ValidatePaymentCreateException;
use yii\base\Model;

class PaymentCreateForm extends Model
{

    public function rules(): array
    {
        return [

        ];
    }

    public function attributeLabels(): array
    {
        return [

        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidatePaymentCreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidatePaymentCreateException($self);
    }
}