<?php

namespace app\modules\client\forms;

use app\models\District;
use app\modules\api\validators\PhoneValidator;
use app\modules\client\exceptions\ValidateClientCreateException;
use yii\base\Model;

class ClientCreateForm extends Model
{

    public $name;
    public $surname;
    public $patronymic;
    public $phone;
    public $additional_phone;
    public $residence_address;
    public $work_address;
    public $district_id;
    public $files;
    public $activity;
    public $profit;
    public $comment;

    public function rules(): array
    {
        return [
            [['name', 'surname', 'phone', 'district_id'], 'required'],
            [['name', 'surname', 'patronymic', 'activity', 'profit', 'comment'], 'string'],
            [['phone', 'additional_phone'], PhoneValidator::class],
            [['residence_address', 'work_address'], 'string'],
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
            'residence_address' => 'Адрес проживания',
            'work_address' => 'Адрес работы',
            'district_id' => 'Район',
            'files' => 'Фотографии',
            'activity' => 'Вид деятельности',
            'profit' => 'Выручка в день',
            'comment' => 'Комментарий',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateClientCreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateClientCreateException($self);
    }
}