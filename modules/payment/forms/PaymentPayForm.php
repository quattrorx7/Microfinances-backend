<?php

namespace app\modules\payment\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use app\models\Payment;
use yii\base\Model;

class PaymentPayForm extends Model
{
    public $id;

    public $amount;

    public $in_cart = false;


    public function rules(): array
    {
        return [
            [['amount'], 'required'],
            [['amount'], 'integer'],
            [['in_cart'], 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Сумма',
            'in_cart' => 'На карту',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateException
     */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}