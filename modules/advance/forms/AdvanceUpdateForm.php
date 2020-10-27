<?php

namespace app\modules\advance\forms;

use app\modules\advance\exceptions\ValidateAdvanceUpdateException;
use yii\base\Model;

class AdvanceUpdateForm extends Model
{

    public function rules(): array
    {
        return [

        ];
    }

    public function attributeLabels(): array
    {
        return [

        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateAdvanceUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateAdvanceUpdateException($self);
    }
}