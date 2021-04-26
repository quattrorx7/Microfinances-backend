<?php

namespace app\modules\client\forms;

use app\models\District;
use app\models\User;
use app\modules\api\validators\PhoneValidator;
use app\modules\client\exceptions\ValidateClientUpdateException;
use yii\base\Model;

class ClientOwnerForm extends Model
{

   
    public $user_id;


    public function rules(): array
    {
        return [
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Сотрудник'
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateClientUpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateClientUpdateException($self);
    }
}