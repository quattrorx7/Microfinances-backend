<?php

namespace app\modules\paymenthistory\components;

use app\components\BaseRepository;
use app\helpers\DateHelper;
use app\models\Payment;
use app\models\PaymentHistory;
use app\modules\payment\exceptions\PaymentNotFoundException;

class PaymentHistoryRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return Payment|array|\yii\db\ActiveRecord
    * @throws PaymentNotFoundException
    */
    public function getPaymentHistoryById(int $id)
    {
        $model = PaymentHistory::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new PaymentNotFoundException('Payment History не найден');
        }

        return $model;
    }

}