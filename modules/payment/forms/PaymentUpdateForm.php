<?php

namespace app\modules\payment\forms;

use app\modules\payment\exceptions\ValidatePaymentUpdateException;
use yii\base\Model;

class PaymentUpdateForm extends Model
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
    * @throws ValidatePaymentUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidatePaymentUpdateException($self);
    }
}