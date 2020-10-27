<?php

namespace app\modules\user\forms;

use app\modules\user\exceptions\ValidateUserUpdateException;
use yii\base\Model;

class UserUpdateForm extends Model
{
    public $username;

    public $fullname;

    public function rules(): array
    {
        return [
            ['username', 'integer', 'min' => 10000000, 'max' => 99999999],
            [['fullname'], 'required'],
            [['fullname'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fullname' => 'ФИО сотрудника',
            'username' => 'Идентификатор пользователя',
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