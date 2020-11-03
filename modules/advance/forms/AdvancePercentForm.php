<?php

namespace app\modules\advance\forms;

use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvancePercentForm extends Model
{
    public $amount;
    public $limitation;
    public $daily_payment;

    public function rules(): array
    {
        return [
            [['amount', 'limitation', 'daily_payment'], 'required'],
            [['amount'], 'integer'],
            ['limitation', 'integer', 'min' => 1],
            ['amount', 'integer', 'min' => 1],
            ['daily_payment', 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Сумма',
            'limitation' => 'Срок займа',
            'daily_payment' => 'Ежедневный платеж',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateAdvanceCreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateAdvanceCreateException($self);
    }
}