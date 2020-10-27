<?php

namespace app\modules\advance\forms;

use app\models\Advance;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceCreateWithClientForm extends Model
{

    public $created_at;
    public $amount;
    public $limitation;

    public function rules(): array
    {
        return [
            ['created_at', 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'amount', 'limitation'], 'required'],
            ['amount', 'integer'],
            ['limitation', 'in', 'range' => [Advance::DAYS_15, Advance::DAYS_30, Advance::DAYS_45, Advance::DAYS_60]]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'created_at' => 'Дата выдачи',
            'amount' => 'Сумма',
            'limitation' => 'Срок займа',
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