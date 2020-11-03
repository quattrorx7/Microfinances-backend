<?php

namespace app\modules\advance\forms;

use app\models\Client;
use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceCreateByClientForm extends Model
{
    public $issue_date;
    public $amount;
    public $limitation;
    public $user_id;
    public $client_id;

    public function rules(): array
    {
        return [
            ['issue_date', 'date', 'format' => 'php:Y-m-d'],
            [['issue_date', 'amount', 'limitation', 'user_id'], 'required'],
            [['amount', 'user_id', 'client_id'], 'integer'],
            ['limitation', 'integer', 'min' => 1],
            ['amount', 'integer', 'min' => 1],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'issue_date' => 'Дата выдачи',
            'amount' => 'Сумма',
            'limitation' => 'Срок займа',
            'user_id' => 'Сотрудник',
            'client_id' => 'Клиент',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
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