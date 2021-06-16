<?php

namespace app\modules\client\forms;

use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class ClientSummaForm extends Model
{

    public $balance;

    public function rules(): array
    {
        return [
            [['balance'], 'required'],
            ['balance', 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'balance' => 'Баланс',
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