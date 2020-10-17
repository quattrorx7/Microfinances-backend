<?php

namespace app\modules\user\forms;

use app\modules\user\exceptions\ValidateAuthException;
use yii\base\Model;

class AuthForm extends Model
{

    public $id;
    public $password;
    public $device_id;

    public function rules(): array
    {
        return [
            ['id', 'integer', 'min' => 10000000, 'max' => 99999999],
            [['password', 'id', 'device_id'], 'required'],
            [['password', 'device_id'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID сотрудника',
            'password' => 'Пароль',
            'device_id' => 'ID устройства',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateAuthException
     */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateAuthException($self);
    }
}