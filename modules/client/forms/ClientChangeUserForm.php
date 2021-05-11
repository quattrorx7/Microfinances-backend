<?php

namespace app\modules\client\forms;

use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class ClientChangeUserForm extends Model
{

    public $user_id;

    public function rules(): array
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Сотрудник',
        ];
    }

    /**
     * @param $bodyParams
     * @param string $formName
     * @param bool $isAdmin
     * @return static
     * @throws ValidateAdvanceCreateException
     */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateAdvanceCreateException($self);
    }
}