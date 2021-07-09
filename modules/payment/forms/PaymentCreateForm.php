<?php

namespace app\modules\payment\forms;

use app\modules\payment\exceptions\ValidatePaymentCreateException;
use yii\base\Model;

class PaymentCreateForm extends Model
{
    public $date_pay;

    public function rules(): array
    {
        return [
            ['date_pay', 'date', 'format' => 'php:Y-m-d'],
            [['date_pay'], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'date_pay' => 'Дата платежа',
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