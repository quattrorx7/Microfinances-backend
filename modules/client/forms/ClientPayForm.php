<?php

namespace app\modules\client\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use yii\base\Model;

class ClientPayForm extends Model
{
    public $advance_ids;

    public $amount;

    public $in_cart = false;

    public function rules(): array
    {
        return [
            [['advance_ids', 'amount'], 'required'],
            [['advance_ids'], 'each', 'rule' => ['integer']],
            [['advance_ids'], 'each', 'rule' => [
                'exist', 'targetClass' => Advance::class, 'targetAttribute' => ['advance_ids' => 'id'], 'filter' => 'payment_status <> 8']
            ],
            [['amount'], 'integer'],
            [['in_cart'], 'boolean']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'advance_ids' => 'Займы',
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