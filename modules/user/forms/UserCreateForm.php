<?php

namespace app\modules\user\forms;

use app\modules\user\exceptions\ValidateUserCreateException;
use yii\base\Model;

class UserCreateForm extends Model
{

    public $username;
    public $fullname;
    public $password;
    public $repeat_password;

    public function rules(): array
    {
        return [
            ['username', 'integer', 'min' => 10000000, 'max' => 99999999],
            [['password', 'repeat_password', 'username', 'fullname'], 'required'],
            [['password', 'repeat_password', 'fullname'], 'string'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fullname' => 'ФИО сотрудника',
            'username' => 'Идентификатор пользователя',
            'password' => 'Пароль',
            'repeat_password' => 'Повторите пароль',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateUserCreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateUserCreateException($self);
    }
}