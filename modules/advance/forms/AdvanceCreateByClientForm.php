<?php

namespace app\modules\advance\forms;

use app\models\Client;
use app\models\User;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use yii\base\Model;

class AdvanceCreateByClientForm extends Model
{
    public const SCENARIO_ADMIN = 'admin';
    public const SCENARIO_USER = 'user';

    public $issue_date;
    public $amount;
    public $limitation;
    public $user_id;
    public $client_id;
    public $daily_payment;

    public function scenarios()
    {
        return [
            self::SCENARIO_ADMIN => ['issue_date', 'amount', 'limitation', 'user_id', 'daily_payment', 'client_id'],
            self::SCENARIO_USER => ['issue_date', 'amount', 'limitation', 'user_id', 'client_id'],
        ];
    }

    public function rules(): array
    {
        return [
            ['issue_date', 'date', 'format' => 'php:Y-m-d'],
            [['issue_date', 'amount', 'limitation', 'client_id'], 'required', 'on' => self::SCENARIO_USER],
            [['issue_date', 'amount', 'limitation', 'user_id', 'daily_payment', 'client_id'], 'required', 'on' => self::SCENARIO_ADMIN],
            [['amount', 'user_id', 'client_id'], 'integer'],
            ['limitation', 'integer', 'min' => 1],
            ['amount', 'integer', 'min' => 1],
            ['daily_payment', 'integer'],
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
            'daily_payment' => 'Ежедневный платеж',
        ];
    }

    /**
     * @param $bodyParams
     * @param string $formName
     * @param bool $isAdmin
     * @return static
     * @throws ValidateAdvanceCreateException
     */
    public static function loadAndValidate($bodyParams, $formName = '', $isAdmin = false): self
    {
        $self = new self();
        $self->scenario = $isAdmin ? $self::SCENARIO_ADMIN : self::SCENARIO_USER;
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateAdvanceCreateException($self);
    }
}