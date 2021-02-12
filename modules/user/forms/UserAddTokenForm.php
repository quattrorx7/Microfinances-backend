<?php

namespace app\modules\user\forms;

use app\modules\user\exceptions\ValidateUserUpdateException;
use yii\base\Model;

class UserAddTokenForm extends Model
{
    public $token;

    public function rules(): array
    {
        return [
            [['token'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'token' => 'Токен',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateUserUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateUserUpdateException($self);
    }
}