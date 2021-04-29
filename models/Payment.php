<?php

namespace app\models;

use Yii;

/**
 * Class Payment
 * @package app\models
 */
class Payment extends \app\models\base\Payment
{
    //virtual
    public $todayPayed;
    public $debt;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $data = parent::attributeLabels();
        
        $data['full_amount'] = Yii::t('app', 'Платеж за день');
        $data['amount'] = Yii::t('app', 'Осталось заплатить');
 

        return $data;
        // [
        //     'id' => Yii::t('app', 'ID'),
        //     'advance_id' => Yii::t('app', 'Advance ID'),
        //     'client_id' => Yii::t('app', 'Client ID'),
        //     'amount' => Yii::t('app', 'Amount'),
        //     'created_at' => Yii::t('app', 'Created At'),
        //     'user_id' => Yii::t('app', 'User ID'),
        //     'district_id' => Yii::t('app', 'District ID'),
        //     'full_amount' => Yii::t('app', 'Full Amount'),
        // ];
    }


}