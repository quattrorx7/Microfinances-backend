<?php

namespace app\modules\advance\forms;

use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceCreateForm extends Model
{
    public $issue_date;
    public $amount;
    public $limitation;
    public $daily_payment;
    public $user_id;

    public function rules(): array
    {
        return [
            ['issue_date', 'date', 'format' => 'php:Y-m-d'],
            [['issue_date', 'amount', 'limitation', 'user_id', 'daily_payment'], 'required'],
            [['amount', 'user_id'], 'integer'],
            ['limitation', 'integer', 'min' => 1],
            ['daily_payment', 'integer'],
            ['daily_payment', 'default', 'value' => 1],
            ['amount', 'integer', 'min' => 1]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'issue_date' => 'Дата выдачи',
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