<?php

namespace app\modules\payment\forms;

use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class PaymentSummaForm extends Model
{

    public $amount;

    public function rules(): array
    {
        return [
            [['amount'], 'required'],
            ['amount', 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Осталось заплатить',
        ];
    }

    /**
     * @param $bodyParams
     * @param string $formName
     * @param bool $isAdmin
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