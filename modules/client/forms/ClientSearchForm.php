<?php

namespace app\modules\client\forms;

use app\components\exceptions\ValidateException;
use yii\base\Model;

class ClientSearchForm extends Model
{
    public $search;

    public function rules(): array
    {
        return [
            [['search'], 'string']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'search' => 'Поиск',
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