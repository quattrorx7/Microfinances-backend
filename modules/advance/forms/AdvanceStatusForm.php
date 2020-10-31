<?php

namespace app\modules\advance\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use yii\base\Model;

class AdvanceStatusForm extends Model
{
    public $status;

    public function rules(): array
    {
        return [
            [['status'], 'in', 'range' => [Advance::STATE_DENIED, Advance::STATE_ISSUED]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Статус'
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