<?php

namespace app\modules\payment\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use app\models\Payment;
use yii\base\Model;

class PaymentDebtForm extends Model
{
    public $id;

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
            'date_pay' => 'Дата долга',
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