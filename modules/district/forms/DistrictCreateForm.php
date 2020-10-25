<?php

namespace app\modules\district\forms;

use app\models\District;
use app\modules\district\exceptions\ValidateDistrictCreateException;
use yii\base\Model;

class DistrictCreateForm extends Model
{

    public $title;

    public function rules(): array
    {
        return [
            ['title', 'required'],
            ['title', 'unique', 'targetClass' => District::class, 'targetAttribute' => 'title'],
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
    * @throws ValidateDistrictCreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateDistrictCreateException($self);
    }
}