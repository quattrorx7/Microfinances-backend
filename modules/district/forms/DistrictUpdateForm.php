<?php

namespace app\modules\district\forms;

use app\modules\district\exceptions\ValidateDistrictUpdateException;
use yii\base\Model;

class DistrictUpdateForm extends Model
{

    public $title;

    public function rules(): array
    {
        return [
            ['title', 'required'],
            ['title', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название'
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateDistrictUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateDistrictUpdateException($self);
    }
}