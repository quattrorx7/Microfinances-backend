<?php

namespace app\modules\client\forms;

use app\models\District;
use app\modules\api\validators\PhoneValidator;
use app\modules\client\exceptions\ValidateClientUpdateException;
use yii\base\Model;

class ClientUpdateForm extends Model
{

    public $name;
    public $surname;
    public $patronymic;
    public $phone;
    public $additional_phone;
    public $district_id;
    public $files;

    public function rules(): array
    {
        return [
            [['name', 'surname', 'phone', 'district_id'], 'required'],
            [['name', 'surname', 'patronymic'], 'string'],
            [['phone', 'additional_phone'], PhoneValidator::class],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['district_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'phone' => 'Телефона',
            'additional_phone' => 'Дополнительный телефон',
            'district_id' => 'Район',
            'files' => 'Фотографии'
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