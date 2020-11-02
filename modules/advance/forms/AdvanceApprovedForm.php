<?php

namespace app\modules\advance\forms;

use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceApprovedForm extends Model
{
    public $created_at;
    public $amount;
    public $limitation;
    public $user_id;
    public $daily_payment;

    public $summa_with_percent;
    public $percent;

    public function rules(): array
    {
        return [
            ['created_at', 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'amount', 'limitation', 'user_id', 'daily_payment'], 'required'],
            [['amount', 'user_id'], 'integer'],
            ['limitation', 'integer', 'min' => 1],
            ['amount', 'integer', 'min' => 1],
            ['daily_payment', 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'created_at' => 'Дата выдачи',
            'amount' => 'Сумма',
            'limitation' => 'Срок займа',
            'user_id' => 'Сотрудник',
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