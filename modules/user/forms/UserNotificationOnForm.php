<?php

namespace app\modules\user\forms;

use app\modules\user\exceptions\ValidateUserUpdateException;
use yii\base\Model;

class UserNotificationOnForm extends Model
{
    public $on;

    public function rules(): array
    {
        return [
            ['on', 'boolean', ],
            ['on', 'required']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'on' => 'on',
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