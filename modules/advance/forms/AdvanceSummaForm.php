<?php

namespace app\modules\advance\forms;

use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceSummaForm extends Model
{

    public $summa_left_to_pay;

    public function rules(): array
    {
        return [
            [['summa_left_to_pay'], 'required'],
            ['summa_left_to_pay', 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'summa_left_to_pay' => 'Оставшиеся сумма',
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