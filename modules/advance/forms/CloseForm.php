<?php

namespace app\modules\advance\forms;

use app\components\exceptions\ValidateException;
use app\models\Advance;
use app\models\User;
use yii\base\Model;

class CloseForm extends Model
{

    public $advance_ids;
    public $in_cart = false;


    public function rules(): array
    {
        return [
            [['advance_ids'], 'required'],
            [['advance_ids'], 'each', 'rule' => ['integer']],
            [['advance_ids'], 'each', 'rule' => [
                'exist', 'targetClass' => Advance::class, 'targetAttribute' => ['advance_ids' => 'id'], 'filter' => 'payment_status = 4']
            ],
            [['in_cart'], 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'advance_ids' => 'Займы',
            'in_cart' => 'На карту',
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateException
     */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}