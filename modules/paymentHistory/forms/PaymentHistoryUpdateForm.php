<?php

namespace app\modules\paymenthistory\forms;

use app\modules\payment\exceptions\ValidatePaymentUpdateException;
use app\modules\paymenthistory\exceptions\ValidatePaymentHistoryUpdateException;
use yii\base\Model;

class PaymentHistoryUpdateForm extends Model
{

    public $amount;
    public $debt;
    public $message;
    public $type;


    public function rules(): array
    {
        return [
            [['amount', 'debt', 'type'], 'integer'],
            [['amount', 'debt', 'type'], 'required'],
            ['debt', 'integer', 'min' => 0],
            ['amount', 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Сумма',
            'debt' => 'Долг',
            'type' => 'Тип платежа',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateUserUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidatePaymentHistoryUpdateException($self);
    }
}