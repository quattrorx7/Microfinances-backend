<?php

namespace app\modules\advance\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use yii\base\Model;

class RefinancingForm extends Model
{
    public const SCENARIO_ADMIN = 'admin';
    public const SCENARIO_USER = 'user';
    public const SCENARIO_ADMIN_CREATE = "admin_create";

    public $advance_ids;

    public $amount;

    public $limitation;

    public $daily_payment;

    public function scenarios()
    {
        return [
            self::SCENARIO_ADMIN => ['amount', 'limitation', 'daily_payment'],
            self::SCENARIO_ADMIN_CREATE => ['advance_ids', 'amount', 'limitation', 'daily_payment'],
            self::SCENARIO_USER => ['advance_ids', 'amount', 'limitation'],
        ];
    }

    public function rules(): array
    {
        return [
            [['amount'], 'required'],
            [['daily_payment'], 'required', 'on' => self::SCENARIO_ADMIN],
            [['advance_ids'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            [['advance_ids'], 'required', 'on' => self::SCENARIO_USER],
            [['advance_ids'], 'each', 'rule' => ['integer']],
            [['advance_ids'], 'each', 'rule' => [
                'exist', 'targetClass' => Advance::class, 'targetAttribute' => ['advance_ids' => 'id'], 'filter' => 'payment_status <> 8']
            ],
            [['amount'], 'integer'],
            [['limitation'], 'integer'],
           
            ['daily_payment', 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'advance_ids' => 'Займы',
            'amount' => 'Сумма',
            'limitation' => 'Срок займа',
            'daily_payment' => 'Ежедневный платеж',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateException
     */
    public static function loadAndValidate($bodyParams, $formName = '', $isAdmin = false, $create=false): self
    {
        $self = new self();
        if($isAdmin && $create){
            $self->scenario = $self::SCENARIO_ADMIN_CREATE;
        }else{
            $self->scenario = $isAdmin ? $self::SCENARIO_ADMIN : self::SCENARIO_USER;
        }
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}